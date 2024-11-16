<?php

namespace App\Form;

use App\Entity\Livre;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\Event\PreSubmitEvent;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Component\Form\Event\PostSubmitEvent;

class LivreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                // Cette option définit ce que Symfony va attribuer comme valeur au champ si l'utilisateur ne remplit pas ce champ. on aura une chaine vide aulieu de null
                'empty_data' => ''
            ])
            ->add('slug', TextType::class, [
                'required' => false
            ])
            ->add('author', TextType::class, [
                'required' => false
            ])
            ->add('publicationYear', IntegerType::class, [
                'empty_data' => 0 // Vous pouvez utiliser une autre valeur par défaut si nécessaire, comme -1 ou une autre valeur spécifique
            ])
            /*
            ->add('createdAt', null, [
                'widget' => 'single_text',
                'empty_data' => (new \DateTime())->format('Y-m-d') // Par défaut, vous pouvez mettre la date actuelle ou toute autre valeur
            ])
            ->add('updatedAt', null, [
                'widget' => 'single_text',
                'empty_data' => (new \DateTime())->format('Y-m-d') // Idem pour une date par défaut
            ])
            */
            ->add('genre', TextType::class, [
                'empty_data' => '' // Chaîne vide si aucune donnée n'est entrée
            ])
            ->add('summary', TextType::class, [
                'empty_data' => '' // Chaîne vide si aucune donnée n'est entrée
            ])
            ->add('publisher', TextType::class, [
                'empty_data' => '' // Chaîne vide si aucune donnée n'est entrée
            ])
            ->add('language', TextType::class, [
                'empty_data' => '' // Chaîne vide si aucune donnée n'est entrée
            ])
            ->add('edition', TextType::class, [
                'empty_data' => '' // Chaîne vide si aucune donnée n'est entrée
            ])
            ->add('coverImage', TextType::class, [
                'required' => false, // Rend le champ facultatif // Si cette ligne est absente il nous impose de remplir le champ avant de valider le formulaire
                'empty_data' => '' // Cela reste une option pour définir une chaîne vide par défaut si nécessaire
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Envoyer'
            ])

            // La partie SUBMIT est celle qui va rentrer les informations dans notre model. Nous on agit avant sur le PRE-SUBMIT
            // PRE_SUBMIT qui génère automatiquement le slug à partir du titre du recipe avant la soumission du formulaire.
            // Le second parametre est la fn qu'on souhaite excecuter avant la soumission du formulaire
            ->addEventListener(FormEvents::PRE_SUBMIT, $this->autoslug(...)) // https://chatgpt.com/c/66ef22b8-93bc-800a-b113-6d4d22c5f145
            // POST_SUBMIT qui met à jour les horodatages (par exemple, createdAt et updatedAt) après la soumission du formulaire.
            ->addEventListener(FormEvents::POST_SUBMIT, $this->attachTimestamps(...))



        ;
    }

    public function autoslug(PreSubmitEvent $event): void
    {
        $data = $event->getData();
        if (empty($data['slug'])) {
            $slugger = new AsciiSlugger();
            $data['slug'] = strtolower($slugger->slug($data['title']));
            $event->setData($data);
        }
    }

    public function attachTimestamps(PostSubmitEvent $event): void
    {
        $data = $event->getData();
        if (!($data instanceof Livre)) {
            return;
        }

        $data->setUpdatedAt(new \DateTimeImmutable());
        if (!$data->getId()) {
            $data->setCreatedAt(new \DateTimeImmutable());
        }


    }


     // La méthode configureOptions est utilisée pour configurer les options du formulaire.
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // l'option par défaut data_class est définie sur la classe Recipe, indiquant que le formulaire doit être lié à des objets de cette classe, ici la class Recipe.
            'data_class' => Livre::class,
        ]);
    }
}




// https://chatgpt.com/c/67336566-a2d8-800a-9f73-db25477e0f81

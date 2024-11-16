<?php

namespace App\Repository;

use App\Entity\Livre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Livre>
 */
class LivreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Livre::class);
    }




    /**
     * Calcule et retourne la somme totale de toutes les valeurs du champ publicationYear de l'entité.
     *
     * @return int La somme totale des années de publication.
     */
    public function findTotalYear(): int
    {
        // Création d'un QueryBuilder pour construire une requête sur l'entité associée au repository, avec l'alias 'l'.
        return $this->createQueryBuilder('l')
            // Sélectionne la somme de la colonne publicationYear et l'alias 'total' pour le résultat.
            ->select('SUM(l.publicationYear) as total')
            // Exécute la requête et récupère le résultat en tant que valeur scalaire unique (un entier dans ce cas).
            ->getQuery()
            ->getSingleScalarResult();
    }



    /**
     * Trouve et retourne les enregistrements dont l'année de publication est inférieure ou égale à une valeur donnée.
     *
     * @param int $publicationYear L'année de publication maximale à comparer.
     * @return array Un tableau contenant les résultats correspondant aux critères de la requête.
     */
    public function findWithPublicationYearLowerThan(int $publicationYear): array
    {
        // Création d'un QueryBuilder pour construire une requête sur l'entité associée au repository.
        return $this->createQueryBuilder('l')
            // Ajoute une condition "WHERE" pour sélectionner les enregistrements dont l'année de publication
            // est inférieure ou égale à la valeur fournie en paramètre.
            ->where('l.publicationYear <= :publicationYear')
            // Trie les résultats par année de publication en ordre croissant (ASC).
            ->orderBy('l.publicationYear', 'ASC')
            // Limite les résultats de la requête à un maximum de 10 enregistrements.
            ->setMaxResults(10)
            // Définit la valeur du paramètre ":publicationYear" utilisé dans la clause "WHERE".
            ->setParameter('publicationYear', $publicationYear)
            // Exécute la requête et récupère les résultats sous forme d'un tableau d'objets.
            ->getQuery()
            ->getResult();
    }



























    //    /**
//     * @return Livre[] Returns an array of Livre objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('l.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

    //    public function findOneBySomeField($value): ?Livre
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}

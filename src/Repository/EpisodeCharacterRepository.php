<?php

namespace App\Repository;

use App\Entity\EpisodeCharacter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<EpisodeCharacter>
 *
 * @method EpisodeCharacter|null find($id, $lockMode = null, $lockVersion = null)
 * @method EpisodeCharacter|null findOneBy(array $criteria, array $orderBy = null)
 * @method EpisodeCharacter[]    findAll()
 * @method EpisodeCharacter[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EpisodeCharacterRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EpisodeCharacter::class);
    }

    //    /**
    //     * @return EpisodeCharacter[] Returns an array of EpisodeCharacter objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('e.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?EpisodeCharacter
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}

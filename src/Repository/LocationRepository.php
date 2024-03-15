<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Location;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Location>
 *
 * @method Location|null find($id, $lockMode = null, $lockVersion = null)
 * @method Location|null findOneBy(array $criteria, array $orderBy = null)
 * @method Location[]    findAll()
 * @method Location[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LocationRepository extends ServiceEntityRepository
{
    private EntityManagerInterface $entityManager;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        parent::__construct($registry, Location::class);
    }

    public function findByFilters(array $filters): array
    {
        $qb = $this->createQueryBuilder('l');

        foreach ($filters as $field => $value) {
            $qb->andWhere("l.$field = :$field")
                ->setParameter($field, $value);
        }

        return $qb->getQuery()->getResult();
    }

    public function getTotalEntityCount(): int
    {
        return $this->createQueryBuilder('p')
            ->select('COUNT(p.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function save(Location $location): void
    {
        $this->entityManager->persist($location);
        $this->entityManager->flush();
    }

    public function remove(Location $location): void
    {
        $this->entityManager->remove($location);
        $this->entityManager->flush();
    }

    public function getTotalEntityCountWithFilters(array $filters): int
    {
        $qb = $this->createQueryBuilder('l')
            ->select('COUNT(l.id)');

        foreach ($filters as $field => $value) {
            $qb->andWhere("l.$field = :$field")
                ->setParameter($field, $value);
        }

        return (int) $qb->getQuery()->getSingleScalarResult();
    }

}

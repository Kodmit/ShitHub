<?php

namespace App\Repository;

use App\Entity\Task;
use DateTimeImmutable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Task|null find($id, $lockMode = null, $lockVersion = null)
 * @method Task|null findOneBy(array $criteria, array $orderBy = null)
 * @method Task[]    findAll()
 * @method Task[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TaskRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Task::class);
    }

    public function getTasksAndFilter(
        bool $done = null,
        DateTimeImmutable $updatedStartDate = null,
        DateTimeImmutable $updatedEndDate = null
    ): array
    {
        $qb = $this
            ->createQueryBuilder('t')
            ;

        $conditionTriggered = false;

        if (null !== $done) {
            $qb
                ->where('t.done = :done')
                ->setParameter('done', $done)
                ;
            $conditionTriggered = true;
        }

        if (null !== $updatedStartDate) {
            if (true === $conditionTriggered) {
                $qb->andWhere('t.updatedAt > :updatedStartDate');
            } else {
                $qb->where('t.updatedAt > :updatedStartDate');
            }
            $qb->setParameter('updatedStartDate', $updatedStartDate->format('Y-m-d H:i:s'));
            $conditionTriggered = true;
        }

        if (null !== $updatedEndDate) {
            if (true === $conditionTriggered) {
                $qb->andWhere('t.updatedAt < :updatedEndDate');
            } else {
                $qb->where('t.updatedAt < :updatedEndDate');
            }
            $qb->setParameter('updatedEndDate', $updatedEndDate->format('Y-m-d H:i:s'));
        }

        return $qb
            ->getQuery()
            ->getResult()
            ;
    }
}

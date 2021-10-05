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
        DateTimeImmutable $doneStartDate = null,
        DateTimeImmutable $doneEndDate = null
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

        if (null !== $doneStartDate) {
            if (true === $conditionTriggered) {
                $qb->andWhere('t.doneAt > :doneStartDate');
            } else {
                $qb->where('t.doneAt > :doneStartDate');
            }
            $qb->setParameter('doneStartDate', $doneStartDate->format('Y-m-d H:i:s'));
            $conditionTriggered = true;
        }

        if (null !== $doneEndDate) {
            if (true === $conditionTriggered) {
                $qb->andWhere('t.doneAt < :doneEndDate');
            } else {
                $qb->where('t.doneAt < :doneEndDate');
            }
            $qb->setParameter('doneEndDate', $doneEndDate->format('Y-m-d H:i:s'));
        }

        return $qb
            ->getQuery()
            ->getResult()
            ;
    }
}

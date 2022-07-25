<?php

    namespace App\Repository;

    use App\Entity\Event;
    use App\Entity\Participant;
    use App\Form\DTO\EventDTO;
    use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
    use Doctrine\Persistence\ManagerRegistry;

    /**
     * @extends ServiceEntityRepository<Event>
     *
     * @method Event|null find($id, $lockMode = null, $lockVersion = null)
     * @method Event|null findOneBy(array $criteria, array $orderBy = null)
     * @method Event[]    findAll()
     * @method Event[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
     */
    class EventRepository extends ServiceEntityRepository
    {
        public function __construct(ManagerRegistry $registry)
        {
            parent::__construct($registry, Event::class);
        }

        public function add(Event $entity, bool $flush = false): void
        {
            $this->getEntityManager()->persist($entity);

            if ($flush) {
                $this->getEntityManager()->flush();
            }
        }

        public function remove(Event $entity, bool $flush = false): void
        {
            $this->getEntityManager()->remove($entity);

            if ($flush) {
                $this->getEntityManager()->flush();
            }
        }

        /*
         * @return Event[] Returns an array of Event objects
         */

        public function findByFilter(EventDTO $eventDTO, Participant $participant)
        {


            $query = $this->createQueryBuilder('e');

            if (!empty($eventDTO->getCampus())) {
                $query->where('e.campus= :campus')
                    ->setParameter('campus',  $eventDTO->getCampus());
            }
            if (!empty($eventDTO->getName())) {
                $query->andWhere('e.name LIKE :name')
                    ->setParameter('name', '%' . $eventDTO->getName() . '%');
            }
            if (!empty($eventDTO->getIsTheOrganiser())) {
                $query->andWhere('e.organiser = :participant')
                    ->setParameter('participant', $eventDTO->getIsTheOrganiser() );
            }
            if (!empty($eventDTO->getPastEvent())) {
                $query->andWhere('e.status= :status')
                    ->setParameter('status', 'Fermé');
            }
            if (!empty($eventDTO->getEventAttendenceTrue())) {
                $query->andWhere(':participant MEMBER OF e.eventAttendence')
                    ->setParameter('participant', $participant);
            }
            if (!empty($eventDTO->getEventAttendenceFalse())) {
                $query->andWhere(':participant NOT MEMBER OF e.eventAttendence')
                    ->setParameter('participant', $participant);
            }
            if(!empty($eventDTO->getFromDate()) && !empty($eventDTO->getToDate()))
            {
                $query->andWhere('e.startsAt BETWEEN :FromDate AND :ToDate' )
                    ->setParameter('FromDate', $eventDTO->getFromDate())
                    ->setParameter('ToDate', $eventDTO->getToDate());

            }
            if(!empty($eventDTO->getFromDate()) && empty(($eventDTO->getToDate()))){
                $query->andWhere('e.startsAt > :FromDate')
                    ->setParameter('FromDate', $eventDTO->getFromDate());
            }
            if(empty($eventDTO->getFromDate()) && !empty(($eventDTO->getToDate()))){
                $query->andWhere('e.startsAt < :ToDate')
                    ->setParameter('ToDate', $eventDTO->getToDate());
            }
            return $query->getQuery()->getResult();
        }



//    /**
//     * @return Event[] Returns an array of Event objects
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

//    public function findOneBySomeField($value): ?Event
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
    }

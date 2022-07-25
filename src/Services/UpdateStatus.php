<?php

    namespace App\Services;

    use App\Entity\Event;
    use App\Repository\EventRepository;

    class UpdateStatus
    {
        public function updateStatus(EventRepository $eventRepository)
        {

            $events = $eventRepository->findAll();

            foreach ($events as $event){

                //Nombre d'inscrits
                $particicipants = $event->getEventAttendence()->count();
                //capacité
                $capacityEvent = $event->getCapacity();
                //places restantes
                $availablePlaces = $capacityEvent - $particicipants;

                //Date du jour
                $dateOfTheDay = new \DateTime();
                //Cloture inscription
                $cutOffDate = $event->getCutOffDate();
                //Date de l'event
                $StartsAtDate = $event->getStartsAt();
                //Date d'archivage? $ArchiveDate


                if ($availablePlaces == 0) {
                    $event->setStatus('fermé');
                }
                if ($dateOfTheDay > $cutOffDate) {
                    $event->setStatus('fermé');
                }
                if ($dateOfTheDay == $StartsAtDate) {
                    $event->setStatus('En cours');
                }
                /* if($dateOfTheDay >= $ArchiveDate) {
                     $event->setStatus('Historisé');}*/

        }



        }


    }
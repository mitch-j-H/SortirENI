<?php

    namespace App\Services;

    use App\Entity\Event;
    use App\Repository\EventRepository;

    class UpdateStatus
    {
        public function updateStatus(EventRepository $eventRepository)
        {
            $events = $eventRepository->findAll();

            foreach ($events as $event) {

                // on récupère le status des events
                $status = $event->getStatus();

                //Date du jour
                $dateOfTheDay = new \DateTime('+ 2 hours');



                //Durée de la sortie
                $duration = $event->getDuration();
                //date cloture inscription
                $cutOffDate = $event->getCutOffDate();
                //Date de l'event
                $StartsAtDate = $event->getStartsAt();
                //Date d'archivage? $ArchiveDate
                $ArchiveDate = clone $StartsAtDate;
                $ArchiveDate->modify('+ 1 month');
                //fin de la sortie
                $afterEvent = clone $StartsAtDate;
                $afterEvent->modify(+$duration . ' minutes');



                if ($status != 'Annulée') {


                    //Cloturé
                    if ($dateOfTheDay >= $cutOffDate and $dateOfTheDay<$StartsAtDate) {
                        $event->setStatus("Cloturée");
                    }

                    // en cours

                   else if ($dateOfTheDay >= $StartsAtDate and $dateOfTheDay<$afterEvent) {
                        $event->setStatus('En cours');
                    }

                    if ($dateOfTheDay >= $afterEvent) {
                        $event->setStatus('Terminée');

                    }
                }
                //Historisé

                if ($dateOfTheDay >= $ArchiveDate) {
                    $event->setStatus('Historisée');

                }


            }


        }


    }
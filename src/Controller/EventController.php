<?php

    namespace App\Controller;

    use App\Entity\Event;
    use App\Entity\Reason;
    use App\Form\CancelEventType;
    use App\Form\DTO\EventDTO;
    use App\Form\EventType;
    use App\Form\FilterEventType;
    use App\Repository\EventRepository;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;


    #[Route('/events', name: 'event_')]
    class EventController extends AbstractController
    {
        #[Route('', name: 'list')]
        public function list(EventRepository $eventRepository, Request $request, EventDTO $eventDTO): Response
        {
            //todo: prochaine ligne a supprimer quand le filtre fonctionne?
            $events = $eventRepository->findAll();



            $EventDTO = new EventDTO();

            $filterForm = $this->createForm(FilterEventType::class, $EventDTO);
            $filterForm->handleRequest($request);

            if($filterForm->isSubmitted() )
            {
                $eventDTO= $filterForm->getData();
                $events = $eventRepository->findByFilter($eventDTO, $this->getUser());

            }

            return $this->render('event/list.html.twig', [
                'events' => $events,
                'filterForm' => $filterForm->createView()
            ]);
        }

        #[Route('/details/{id}', name: 'detail')]
        public function details(int $id, EventRepository $eventRepository): Response
        {
            $event = $eventRepository->find($id);

            //todo: creer un service/methode qui permet de calculer le nbre de places restantes (+change le statut)

            //Nombre d'inscrits
            $particicipants = $event->getEventAttendence()->count();
            //capacité
            $capacityEvent = $event->getCapacity();
            //places restantes
            $availablePlaces = $capacityEvent - $particicipants;

            $event = $eventRepository->find($id);


            return $this->render('event/detail.html.twig', [
                "event" => $event,
                'availablePlaces' => $availablePlaces
            ]);
        }

        #[Route('/create', name: 'create')]
        public function create(Request $request, EntityManagerInterface $entityManager): Response
        {

            $event = new Event();

            $event->setOrganiser($this->getUser());

            $eventForm = $this->createForm(EventType::class, $event);

            $eventForm->handleRequest($request);
            //changer par une methode create status fonction du bouton de validation
            $event->setStatus('ouvert');

            if($eventForm->get('save')->isClicked() && $eventForm->isValid()){
                $event->setStatus('Créee');

                $entityManager->persist($event);
                $entityManager->flush();
            }

            if($eventForm->get('publish')->isClicked() && $eventForm->isValid()){
                $event->setStatus('Ouverte');

                $entityManager->persist($event);
                $entityManager->flush();
            }

            if ($eventForm->isSubmitted() && $eventForm->isValid()) {

                $entityManager->persist($event);
                $entityManager->flush();

            }

            return $this->render('event/create.html.twig', [
                'eventForm' => $eventForm->createView()
            ]);
        }

        #[Route ("/cancelEvent/{id}", name: "cancel")]
        public function cancelEvent(int $id, EventRepository $eventRepository, EntityManagerInterface $entityManager, Request $request): Response
        {

            //todo: SECURITE : verifier utilisateur en cours est bien le createur de la sortie

            $event = $eventRepository->find($id);

            $reason = new Reason();

            $CancelEventForm = $this->createForm(CancelEventType::class, $reason);

            $CancelEventForm->handleRequest($request);


            if ($CancelEventForm->isSubmitted() && $CancelEventForm->isValid()) {

                $event->setReason($reason);
                $entityManager->persist($reason);
                $entityManager->flush();

            }
            return $this->render('event/cancel.html.twig', [
                'cancelEventForm' => $CancelEventForm->createView(),
                'event' => $event
            ]);
        }


        #[Route ("/addParticipate/{id}", name: "participate")]
        public function addParticipant(int $id, EventRepository $eventRepository, EntityManagerInterface $entityManager): Response
        {

            $event = $eventRepository->find($id);
            $currentUser = $this->getUser();
            $currentUser->addEventsAttending($event);

            //todo: creer un service/methode qui permet de calculer le nbre de places restantes

            //Nombre d'inscrits
            $particicipants = $event->getEventAttendence()->count();
            //capacité
            $capacityEvent = $event->getCapacity();
            //places restantes
            $availablePlaces = $capacityEvent - $particicipants;

            $entityManager->persist($event);
            $entityManager->flush();

            $this->addFlash('addParticipate', 'Inscription acceptée !');

            return $this->render('event/detail.html.twig', [
                'event' => $event,
                'currentUser' => $currentUser,
                'availablePlaces' => $availablePlaces
            ]);
        }

        /**
         * @Route ("/removeParticipate/{id}", name="removeParticipate")
         */
        public function leaveEvent(int $id, EventRepository $eventRepository, EntityManagerInterface $entityManager): Response
        {
            $event = $eventRepository->find($id);
            $currentUser = $this->getUser();

            $currentUser->removeEventsAttending($event);

            //todo: creer un service/methode qui permet de calculer le nbre de places restantes

            //Nombre d'inscrits
            $particicipants = $event->getEventAttendence()->count();
            //capacité
            $capacityEvent = $event->getCapacity();
            //places restantes
            $availablePlaces = $capacityEvent - $particicipants;

            $entityManager->persist($event);
            $entityManager->flush();

            $this->addFlash('leaveEvent', 'Désinscription acceptée !');

            return $this->render('event/detail.html.twig', [
                'event' => $event,
                'availablePlaces' => $availablePlaces

            ]);
        }


        /*   public function updateStatusFromDateOfTheDayAndAttendence(Event $event){

               $dateOfTheDay = new \DateTime();

               //Nombre d'inscrits
               $particicipants = $event->getEventAttendence()->count();
               //capacité
               $capacityEvent = $event->getCapacity();
               //places restantes
               $availablePlaces = $capacityEvent - $particicipants;

               $cutOffDate = $event->getCutOffDate();
               $StartsAtDate = $event->getStartsAt();
               if($availablePlaces<=0){
                   $event->setStatus('fermé');
               }
               if($dateOfTheDay>$cutOffDate){
                   $event->setStatus('fermé');
               }
               if($dateOfTheDay<$StartsAtDate){
                   $event->setStatus('En cours');
               }
               if($dateOfTheDay >= (strtotime("+1 month", strtotime($StartsAtDate)))) {
                   $event->setStatus('Historisé');
               }else{
                   $event->setStatus('ouvert');
               }

           }*/
    }

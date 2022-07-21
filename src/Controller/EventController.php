<?php

    namespace App\Controller;

    use App\Entity\Event;
    use App\Form\EventType;
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
        public function list(EventRepository $eventRepository): Response
        {
            $events = $eventRepository->findAll();


            return $this->render('event/list.html.twig', [
                'events' => $events
            ]);
        }

        #[Route('/details/{id}', name: 'detail')]
        public function details(int $id, EventRepository $eventRepository): Response
        {
            $event = $eventRepository->find($id);
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

            $eventForm = $this->createForm(EventType::class, $event);

            $eventForm->handleRequest($request);


            if ($eventForm->isSubmitted()) {

                $entityManager->persist($event);
                $entityManager->flush();

            }

            return $this->render('event/create.html.twig', [
                'eventForm' => $eventForm->createView()
            ]);
        }

        /**
         * @Route ("/addParticipate/{id}", name="participate")
         */
        public function addParticipant(int $id, EventRepository $eventRepository, EntityManagerInterface $entityManager): Response
        {

            $event = $eventRepository->find($id);
            $currentUser = $this->getUser();
            $currentUser->addEventsAttending($event);

            //Nombre d'inscrits
            $particicipants = $event->getEventAttendence()->count();
            //capacité
            $capacityEvent = $event->getCapacity();
            //places restantes
            $availablePlaces = $capacityEvent - $particicipants;

            $entityManager->persist($event);
            $entityManager->flush();

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

            //Nombre d'inscrits
            $particicipants = $event->getEventAttendence()->count();
            //capacité
            $capacityEvent = $event->getCapacity();
            //places restantes
            $availablePlaces = $capacityEvent - $particicipants;

            $entityManager->persist($event);
            $entityManager->flush();

            return $this->render('event/detail.html.twig', [
                'event' => $event,
                'availablePlaces' => $availablePlaces

            ]);
        }
    }

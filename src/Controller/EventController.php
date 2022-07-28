<?php

    namespace App\Controller;

    use App\Entity\Event;
    use App\Entity\Reason;
    use App\Form\CancelEventType;
    use App\Form\DTO\EventDTO;
    use App\Form\EventType;
    use App\Form\FilterEventType;
    use App\Repository\EventRepository;
    use App\Services\AvailablePlacesInEvent;
    use App\Services\UpdateStatus;
    use Doctrine\ORM\EntityManagerInterface;
    use Doctrine\ORM\EntityRepository;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
    use Symfony\Component\Routing\Annotation\Route;
    use function PHPUnit\Framework\throwException;


    #[Route('/events', name: 'event_')]
    class EventController extends AbstractController
    {
        #[Route('', name: 'list')]
        public function list(EventRepository $eventRepository, Request $request, UpdateStatus $updateStatus): Response
        {
            $dateOfTheDay = new \DateTime();

            $updateStatus->updateStatus($eventRepository);

            $EventDTO = new EventDTO();

            $filterForm = $this->createForm(FilterEventType::class, $EventDTO);
            $filterForm->handleRequest($request);

            $eventDTO = $filterForm->getData();
            $events = $eventRepository->findByFilter($eventDTO, $this->getUser());

            return $this->render('event/list.html.twig', [
                'events' => $events,
                'filterForm' => $filterForm->createView(),
                'dateOfTheDay' => $dateOfTheDay
            ]);
        }

        #[Route('/details/{id}', name: 'detail')]
        public function details(int $id, EventRepository $eventRepository, UpdateStatus $updateStatus): Response
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

            $event->setOrganiser($this->getUser());

            $eventForm = $this->createForm(EventType::class, $event);

            $eventForm->handleRequest($request);
            //changer par une methode create status fonction du bouton de validation
            $event->setStatus('Ouverte');
            if ($eventForm->get('save')->isClicked() && $eventForm->isValid()) {
                $event->setStatus('En création');

                $entityManager->persist($event);
                $entityManager->flush();
                return $this->redirectToRoute('event_detail', ['id'=>$event->getid()]);
            }

            if ($eventForm->get('publish')->isClicked() && $eventForm->isValid()) {
                $event->setStatus('Ouverte');

                $entityManager->persist($event);
                $entityManager->flush();
                return $this->redirectToRoute('event_detail', ['id'=>$event->getid()]);
            }

            if ($eventForm->isSubmitted() && $eventForm->isValid()) {

                $entityManager->persist($event);
                $entityManager->flush();
                return $this->redirectToRoute('event_detail', ['id'=>$event->getid()]);
            }

            return $this->render('event/create.html.twig', [
                'eventForm' => $eventForm->createView()
            ]);
        }

        #[Route('/modify/{id}', name: 'modify')]
        public function modify(Request $request, EntityManagerInterface $entityManager, int $id, EventRepository $eventRepository): Response
        {
            //pull event by id
            $event = $eventRepository->find($id);

            //verify correct user
            $organiser = $event->getOrganiser();
            if ($organiser != $this->getUser()) {
                throw new UnauthorizedHttpException('Vous n\'êtes pas le créateur de cette sortie, vous ne pouvez pas la modifier');
            }

            //build form
            $eventForm = $this->createForm(EventType::class, $event);
            $eventForm->handleRequest($request);

            //validate form
            if ($eventForm->isSubmitted() && $eventForm->isValid()) {

                //pull form modif and change event object
                $event = $eventForm->getData();
                //update DB
                $entityManager->persist($event);
                $entityManager->flush();
                //redirect to the page details of the object
                return $this->redirectToRoute('event_detail', ['id'=>$event->getid()]);
            }


            return $this->render('event/modify.html.twig', [
                'eventForm' => $eventForm->createView()
//                'event' => $event
            ]);
        }

        #[Route ("/cancelEvent/{id}", name: "cancel")]
        public function cancelEvent(int $id, EventRepository $eventRepository, EntityManagerInterface $entityManager, Request $request): Response
        {

            $event = $eventRepository->find($id);

            $organiser = $event->getOrganiser();

            if ($organiser != $this->getUser()) {
                throw new UnauthorizedHttpException('Vous n\'êtes pas le créateur de cette sortie, vous ne pouvez pas la modifier');
            }

            $reason = new Reason();

            $CancelEventForm = $this->createForm(CancelEventType::class, $reason);

            $CancelEventForm->handleRequest($request);

            if ($CancelEventForm->isSubmitted() && $CancelEventForm->isValid()) {
                $event->setStatus('Annulée');
                $event->setReason($reason);
                $entityManager->persist($reason);
                $entityManager->flush();
                $this->addFlash('cancelSuccess', 'Votre sortie est bien annulée.');
                return $this->redirectToRoute('event_detail',['id'=>$event->getId()]);

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


            if ($capacityEvent-$particicipants == 0) {
                $event->setStatus('Cloturée');
            }

            $entityManager->persist($event);
            $entityManager->flush();

            $this->addFlash('addParticipate', 'Inscription acceptée !');

            return $this->render('event/detail.html.twig', [
                'event' => $event,
                'currentUser' => $currentUser,
                'availablePlaces' => $availablePlaces
            ]);
        }


         #[Route ("/removeParticipate/{id}", name:"removeParticipate")]

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

            if ($availablePlaces > 0) {
                $event->setStatus('Ouverte');
            }
            $entityManager->persist($event);
            $entityManager->flush();

            $this->addFlash('leaveEvent', 'Désinscription acceptée !');

            return $this->render('event/detail.html.twig', [
                'event' => $event,
                'availablePlaces' => $availablePlaces

            ]);
        }


          #[Route ("/removeEvent/{id}", name:"removeEvent")]

        public function removeEvent($id, EntityManagerInterface $entityManager)
        {

            $event = $entityManager->getRepository(Event::class)->find($id);
            $organiser = $event->getOrganiser();

            if ($this->getUser() == $organiser) {
                $entityManager->remove($event);
                $entityManager->flush();
                $this->addFlash('DeleteEvent', 'Votre sortie a bien été supprimée.');
            }
            return $this->redirectToRoute('event_list');
        }

    }

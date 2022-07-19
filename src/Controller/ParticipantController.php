<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Form\ProfileType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

#[Route(path: '/participant', name: 'participant_')]
class ParticipantController extends AbstractController
{
    #[Route(path: '/login', name: 'login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route(path: '/profile', name: 'profile')]
    public function profile(Request $request, EntityManagerInterface $em): Response
    {
        $participant = $this->getUser();
        $profileForm = $this->createForm(ProfileType::class, $participant);

        $profileForm->handleRequest($request);

        if($profileForm->isSubmitted() && $profileForm->isValid()) {
            $participant = new Participant();
            $participant = $profileForm->getData();

            $em->persist($participant);
            $em->flush();
            $this->addFlash('success', 'Profile has been updated');

            return $this->redirectToRoute('participant_profile');
        }
        return $this->render('participant/profile.html.twig', [
            'profileForm' => $profileForm->createView(),
        ]);
    }

}

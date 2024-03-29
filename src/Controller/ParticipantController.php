<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Form\ProfileType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\String\Slugger\SluggerInterface;

//testing to make this the default acces page before touching permissions
//#[Route(path: '/participant', name: 'participant_')]
#[Route(/*path: '/participant',*/ name: 'participant_')]
class ParticipantController extends AbstractController
{
    //testing default access page
//    #[Route(path: '/login', name: 'login')]
    #[Route(path: '/', name: 'login')]
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
        return $this->render('participant/profile.html.twig', [
            'participant' => $participant,
            'profileForm' => $profileForm->createView()
        ]);
    }

    #[Route(path: '/profile-update', name: 'profile-update')]
    public function updateProfile(Request $request, EntityManagerInterface $em, SluggerInterface $slugger,  UserPasswordHasherInterface $hasher): Response
    {
        $participant = $this->getUser();
        $participant2 = $participant->getPassword();
        $profileForm = $this->createForm(ProfileType::class, $participant);
        $profileForm->handleRequest($request);

        if($profileForm->isSubmitted() && $profileForm->isValid()) {
/*            $participant = new Participant();*/
            $participant = $profileForm->getData();
            $imageFile = $profileForm->get('image')->getData();
            if($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();
                try {
                    $imageFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch(FileException $e) {
                }
                $participant->setImage($newFilename);
            }

            $password = $participant->getPassword();
            if (!empty($password)) {
                $hashed = $hasher->hashPassword($participant, $participant->getPassword());
                $participant->setPassword($hashed);
            }
            else {
                $participant->setPassword($participant2);
            }


            $em->persist($participant);
            $em->flush();
            $this->addFlash('success', 'Profil modifié !');

            return $this->redirectToRoute('participant_profile-update');
        }
        return $this->render('participant/profile-update.html.twig', [
            'profileForm' => $profileForm->createView(),
        ]);
    }
    #[Route(path: '/profile/{id}', name: 'participant-profile')]
    public function participantProfile($id, Request $request, EntityManagerInterface $em): Response
    {
        $participant = $em->getRepository(Participant::class)->find($id);
        if ($participant == null) {
            throw $this->createNotFoundException("Utilisateur inconnu !");
        }
        $profileForm = $this->createForm(ProfileType::class, $participant);
        $profileForm->handleRequest($request);

        return $this->render('participant/profile.html.twig', [
            'participant' => $participant,
            'profileForm' => $profileForm->createView(),
        ]);
    }
}

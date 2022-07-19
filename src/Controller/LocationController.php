<?php

namespace App\Controller;

use App\Entity\City;
use App\Entity\Location;

use App\Form\LocationType;
use App\Repository\CityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;



class LocationController extends AbstractController
{
    #[Route('/locations/create', name: 'location_create')]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $location = new Location();

        $locationForm= $this->createForm(LocationType::class, $location);

        //recuperer la liste de city pour completer le locationForm



        $locationForm->handleRequest($request);

        if($locationForm->isSubmitted()){

            $entityManager->persist($location);
            $entityManager->flush();

        }

        return $this->render('location/create.html.twig', [
            'locationForm'=>$locationForm->createView()
        ]);
    }
}

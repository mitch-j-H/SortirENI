<?php

namespace App\Controller\API;

use App\Entity\City;
use App\Entity\Location;
use App\Repository\LocationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/request', name: 'request_')]
class DynamicFormController extends AbstractController
{

    #[Route('/locations/{id}', name: 'locations')]
    public function listLocationsOfCityAction(Request $request, LocationRepository $locationRepository, int $id):JsonResponse
    {
        $locations = $locationRepository->findAllByCity($id);

        $responseArray = array();

        foreach ($locations as $location){
            $responseArray[] = array(
                "id" => $location->getId(),
                "name" => $location->getName(),
                "street_address" => $location->getStreetAddress(),
                "postcode" => $location->getCity()->getPostcode(),
                "latitude" => $location->getLatitude(),
                "longitude" => $location->getLongitude()
            );
        }

        return $this->json($responseArray);
    }
}
<?php

namespace App\Controller;

use App\Entity\Place;
use App\Repository\PlaceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class PlaceController extends AbstractController
{
    #[Route('/place', name: 'app_place', methods:"GET")]
    public function index(PlaceRepository $placeRepository, NormalizerInterface $normalizer): Response
    {
        $places = $placeRepository->findAll();
        $normalized = $normalizer->normalize($places);
        $json = json_encode($normalized);
        $reponse = new Response($json, 201, [
            'content-type' => 'application/json'
        ]);
    
        return $reponse;
    }

    #[Route("/place/{id}", name:"place_avec_id", methods: ['GET'])]
    public function findById(PlaceRepository $placeRepository,NormalizerInterface $normalizer ,int $id): Response
    {
        $place = $placeRepository->find($id);
        $normalized = $normalizer->normalize($place);
        $json = json_encode($normalized);
        $reponse = new Response($json, 200, [
            'content-type' => 'application/json'
        ]);
        return $reponse;
    }

    #[Route('/place/add', name: 'add_place', methods: ['POST'])]
    public function ajoutPlace(Request $request, EntityManagerInterface $entityManager,NormalizerInterface $normalizer ): Response
    {

        $jsonData = $request->getContent();

        $serializer   = new Serializer([new ObjectNormalizer()], [new JsonEncoder()]);
         $place = $serializer->deserialize($jsonData, Place::class,'json');
      
         $entityManager->persist($place);
         $entityManager->flush();
         
        $normalized = $normalizer->normalize($place);
        return new Response(json_encode($normalized), 201, [
            'content-type' => 'application/json'
        ]);
    }
}
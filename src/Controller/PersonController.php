<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\PersonRepository;
use Symfony\Component\Serializer\Context\Normalizer\ObjectNormalizerContextBuilder;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class PersonController extends AbstractController
{
    #[Route('/person', name: 'app_person')]
    public function index(PersonRepository $personRepository,NormalizerInterface $normalizer): Response
        {
            $personnes = $personRepository->findAll();
            $context = (new ObjectNormalizerContextBuilder())
            ->withGroups('person_read')
            ->toArray();
            $normalized = $normalizer->normalize($personnes, null, $context);
            $json = json_encode($normalized);
            $reponse = new Response($json, 200, [
                'content-type' => 'application/json'
                ]);
                return $reponse;
        }
    
    #[Route("/person/{id}", name:"person_avec_id", methods:"GET")]
    public function findById(PersonRepository $personRepository, NormalizerInterface $normalizer, $id):Response
    {
        $person = $personRepository->find($id);
        $context = (new ObjectNormalizerContextBuilder())
            ->withGroups('person_read')
            ->toArray();
        $normalized = $normalizer->normalize($person, null, $context);

        $json = json_encode($normalized);
        $reponse = new Response($json, 200, [
            'content-type' => 'application/json'
        ]);
        return $reponse;
    }
}

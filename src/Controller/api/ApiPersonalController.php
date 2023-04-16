<?php

namespace App\Controller\api;

use App\Entity\Personal;
use App\Form\PersonalType;
use App\Repository\PersonalRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/apipersonal')]
class ApiPersonalController extends AbstractController
{
    #[Route('/list', name: 'app_apipersonal_index', methods: ['GET'])]
    public function index(PersonalRepository $personalRepository): Response
    {
        $personal = $personalRepository->findAll();

        $data = [];

        foreach ($personal as $p) {
            $data[] = [
                'id' => $p->getId(),
                'image' => $p->getImage(),
                'name' => $p->getName(),
                'surname' => $p->getSurname(),
                'rol' => $p->getRol(),
                'vacation' => $p->getVacation(),
                'workshops' => $p->getWorkshops(),
                'signin' => $p->getSignin(),
                'holidays' => $p->getHolidays(),
                'documents' => $p->getDocuments(),
            ];
            
        }

        //dump($data);die; 
        //return $this->json($data);
        return $this->json($data, $status = 200, $headers = ['Access-Control-Allow-Origin'=>'*']);
    }

    #[Route('/create', name: 'app_apipersonal_create', methods: ['POST'])]
    public function create(Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        $personal = new Personal();

        // aquí los datos enviados desde el front
        $data = json_decode($request->getContent(), true);

        // aquí se asignan los datos al personal
        $personal->setName($data['name']);
        $personal->setSurname($data['surname']);
        $personal->setRol($data['rol']);
        $personal->setVacation($data['vacation']);
        $personal->setWorkshops($data['workshops']);
        $personal->setSignin($data['signin']);
        $personal->setHolidays($data['holidays']);
        $personal->setDocuments($data['documents']);

        $entityManager->persist($personal);
        $entityManager->flush();

        return $this->json([
            'message' => 'Personal created successfully'
        ], $status = 201);
    }
}
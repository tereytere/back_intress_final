<?php

namespace App\Controller\api;

use App\Entity\Workshops;
use App\Form\WorkshopsType;
use App\Repository\WorkshopsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/apiworkshops')]
class ApiWorkshopsController extends AbstractController
{
    #[Route('/list', name: 'app_apiworkshops_index', methods: ['GET'])]
    public function index(WorkshopsRepository $workshopsRepository): Response
    {
        $workshops = $workshopsRepository->findAll();

        $data = [];

        foreach ($workshops as $p) {
            $data[] = [
                'id' => $p->getId(),
                'name' => $p->getName(),
                'date' => $p->getDate(),
                'description' => $p->getDescription(),

            ];
            
        }

        //dump($data);die; 
        //return $this->json($data);
        return $this->json($data, $status = 200, $headers = ['Access-Control-Allow-Origin'=>'*']);
    }
}
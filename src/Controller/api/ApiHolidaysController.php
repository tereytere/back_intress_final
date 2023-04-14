<?php

namespace App\Controller;

use App\Entity\Holidays;
use App\Form\HolidaysType;
use App\Repository\HolidaysRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/apiholidays')]
class ApiHolidaysController extends AbstractController
{
    #[Route('/list', name: 'app_apiholidays_index', methods: ['GET'])]
    public function index(HolidaysRepository $holidaysRepository): Response
    {
        $holidays = $holidaysRepository->findAll();

        $data = [];

        foreach ($holidays as $p) {
            $data[] = [
                'id' => $p->getId(),
                'date' => $p->getDate(),
            ];
            
        }

        //dump($data);die; 
        //return $this->json($data);
        return $this->json($data, $status = 200, $headers = ['Access-Control-Allow-Origin'=>'*']);
    }
}
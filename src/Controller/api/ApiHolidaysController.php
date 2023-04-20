<?php

namespace App\Controller\api;

use App\Entity\Holidays;
use App\Form\HolidaysType;
use App\Repository\HolidaysRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

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

    #[Route('/create', name: 'app_apiholidays_create', methods: ['POST'])]
public function create(Request $request, ManagerRegistry $doctrine, SluggerInterface $slugger): Response
{
    $data = json_decode($request->getContent(), true);

    // Validar los datos recibidos

    // Crear Holidays y establecer sus propiedades
    $holiday = new Holidays();
    $holiday->setUser($this->getUser());
    $holiday->setDate(new \DateTime($data['date']));

    // Guarda en la base de datos
    $em = $doctrine->getManager();
    $em->persist($holiday);
    $em->flush();

    return $this->json([
        'id' => $holiday->getId(),
        'date' => $holiday->getDate()->format('Y-m-d'),
    ], $status = 201, $headers = ['Access-Control-Allow-Origin'=>'*']);
}

#[Route('/new', name: 'app_apiholidays_new', methods: ['POST'])]
    public function new(Request $request, ManagerRegistry $doctrine, SluggerInterface $slugger): Response
    {
        $holiday = new Holidays();
        $form = $this->createForm(HolidaysType::class, $holiday);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $em->persist($holiday);
            $em->flush();

            return $this->json(['message' => 'Holiday created!'], $status = 201, $headers = ['Access-Control-Allow-Origin'=>'*']);
        }

        return $this->json(['message' => 'Invalid data'], $status = 400, $headers = ['Access-Control-Allow-Origin'=>'*']);
    }

    #[Route('/{id}', name: 'app_apiholidays_show', methods: ['GET'])]
    public function show(Holidays $holiday): Response
    {
        $data = [
            'id' => $holiday->getId(),
            'date' => $holiday->getDate(),
        ];

        return $this->json($data, $status = 200, $headers = ['Access-Control-Allow-Origin'=>'*']);
    }
    #[Route('/{id}', name: 'app_apiholidays_update', methods: ['PUT'])]
    public function update(Request $request, ManagerRegistry $doctrine, SluggerInterface $slugger, Holidays $holiday): Response
    {
        $form = $this->createForm(HolidaysType::class, $holiday);
        $form->submit(json_decode($request->getContent(), true));

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $em->persist($holiday);
            $em->flush();

            return $this->json(['message' => 'Holiday updated!'], $status = 200, $headers = ['Access-Control-Allow-Origin'=>'*']);
        }

        return $this->json(['message' => 'Invalid data'], $status = 400, $headers = ['Access-Control-Allow-Origin'=>'*']);
    }

    #[Route('/{id}', name: 'app_apiholidays_delete', methods: ['DELETE'])]
    public function delete(Holidays $holiday, ManagerRegistry $doctrine, SluggerInterface $slugger): Response
    {
        $em = $doctrine->getManager();
        $em->remove($holiday);
        $em->flush();

        return $this->json(['message' => 'Holiday deleted!'], $status = 200, $headers = ['Access-Control-Allow-Origin'=>'*']);
    }

}
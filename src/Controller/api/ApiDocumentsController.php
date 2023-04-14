<?php

namespace App\Controller;

use App\Entity\Documents;
use App\Form\DocumentsType;
use App\Repository\DocumentsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/apidocuments')]
class ApiDocumentsController extends AbstractController
{
    #[Route('/list', name: 'app_apidocuments_index', methods: ['GET'])]
    public function index(DocumentsRepository $documentsRepository): Response
    {
        $documents = $documentsRepository->findAll();

        $data = [];

        foreach ($documents as $p) {
            $data[] = [
                'id' => $p->getId(),
                'date' => $p->getDate(),
                'name' => $p->getName(),
                'description' => $p->getDescription(),
                'personal' => $p->getPersonal(),
            ];
            
        }

        //dump($data);die; 
        //return $this->json($data);
        return $this->json($data, $status = 200, $headers = ['Access-Control-Allow-Origin'=>'*']);
    }
}
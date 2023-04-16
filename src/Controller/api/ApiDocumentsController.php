<?php

namespace App\Controller\api;

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
                'description' => $p->getDescription(),
                'document' => $p->getDocument(),
                'docname' => $p->getDocname(),
            ];
            
        }

        //dump($data);die; 
        //return $this->json($data);
        return $this->json($data, $status = 200, $headers = ['Access-Control-Allow-Origin'=>'*']);
    }
}
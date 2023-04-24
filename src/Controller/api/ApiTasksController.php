<?php

namespace App\Controller\api;

use App\Entity\Tasks;
use App\Form\TasksType;
use App\Repository\TasksRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

#[Route('/apitasks')]
class ApiTasksController extends AbstractController
{
    #[Route('/list', name: 'app_apitasks_index', methods: ['GET'])]
    public function index(TasksRepository $tasksRepository): Response
    {
        $tasks = $tasksRepository->findAll();

        $data = [];

        foreach ($tasks as $p) {
            $data[] = [
                'id' => $p->getId(),
                'name' => $p->getName(),
            ];
        }

        return $this->json($data, $status = 200, $headers = ['Access-Control-Allow-Origin'=>'*']);
    }

}
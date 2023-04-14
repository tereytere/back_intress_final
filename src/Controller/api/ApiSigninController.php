<?php

namespace App\Controller;

use App\Entity\Signin;
use App\Form\SigninType;
use App\Repository\SigninRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/apisignin')]
class ApiSigninController extends AbstractController
{
    #[Route('/list', name: 'app_apisignin_index', methods: ['GET'])]
    public function index(SigninRepository $signinRepository): Response
    {
        $signin = $signinRepository->findAll();

        $data = [];

        foreach ($signin as $p) {
            $data[] = [
                'id' => $p->getId(),
                'timestart' => $p->getTimestart(),
                'timerestart' => $p->getTimerestart(),
                'timestop' => $p->getTimestop(),
                'timefinish' => $p->getTimefinish(),
                'hourcount' => $p->getHourcount(),
            ];
            
        }

        //dump($data);die; 
        //return $this->json($data);
        return $this->json($data, $status = 200, $headers = ['Access-Control-Allow-Origin'=>'*']);
    }
}
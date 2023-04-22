<?php

namespace App\Controller\api;

use App\Entity\Signin;
use App\Form\SigninType;
use App\Repository\SigninRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

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

    #[Route('/create', name: 'app_signin_create', methods: ['POST'])]
public function create(Request $request, SigninRepository $signinRepository, JWTTokenManagerInterface $jwtManager, ManagerRegistry $doctrine): Response
{
    $data = json_decode($request->getContent(), true);

    $signin = new Signin();

    $timestart = date('Y-m-d H:i:s'); 

    if (isset($data['startTime'])) {
        $signin->setTimestart($data['startTime']);
    } else {
        $signin->setTimestart($timestart); 
    }

    if (isset($data['endTime'])) {
        $signin->setTimestop($data['endTime']);
    } else {
        
        $signin->setTimestop(date('Y-m-d H:i:s'));
    }

    if (isset($data['pausedTime'])) {
        $signin->setTimerestart($data['pausedTime']);
    }

    if (isset($data['timeFinish'])) {
        $signin->setTimefinish($data['timeFinish']);
    }

    if (isset($data['totalHours'])) {
        $signin->setHourcount($data['totalHours']);
    }

    $em = $doctrine->getManager();
    $em->persist($signin);
    $em->flush();

    $token = $jwtManager->create($this->getUser());

    return $this->json(['status' => 'ok', 'token' => $token]);
}
}
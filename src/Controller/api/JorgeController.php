<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;

class JorgeController extends AbstractController
{

    #[Route('/checkjorge', name:'check_jorge')]

    public function index(Request $request, UserRepository $userRepository, ManagerRegistry $doctrine): Response
    {

        $em = $doctrine->getManager();

        if($request->query->get('bearer')) {
            $token = $request->query->get('bearer');
        }else {
            return $this->redirectToRoute('app_login');
        }

        $tokenParts = explode(".", $token);  
        $tokenHeader = base64_decode($tokenParts[0]);
        $tokenPayload = base64_decode($tokenParts[1]);
        $jwtHeader = json_decode($tokenHeader);
        $jwtPayload = json_decode($tokenPayload);

        dump($jwtPayload);die;
    
        $user = $userRepository->findOneByEmail($jwtPayload->username);

        dump($user->getRoles());die;

        if(!$user) {
            return $this->redirectToRoute('app_login');
        }

        $response = new Response();
        $response->setContent(json_encode([
            'auth' => 'ok',
            'email' => $user->getEmail(),
            'roles' => $user->getRoles()
        ]));
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', '*');
        $response->headers->set('pass', 'ok');
        $response->headers->set('email', $user->getEmail());
        // ¿Una vez con esto la vista puede logarse?
        // $response->headers->setCookie(new Cookie('Authorization', $token));
        // $response->headers->setCookie(new Cookie('BEARER', $token));
        
        return $response; 
    }
}
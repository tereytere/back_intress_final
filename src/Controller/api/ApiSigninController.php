<?php

namespace App\Controller\api;

use App\Entity\Signin;
use App\Form\SigninType;
use App\Repository\SigninRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

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

    #[Route('/create', name: 'app_apisignin_create', methods: ['POST'])]
public function create(Request $request, ManagerRegistry $doctrine): Response
{
    $data = json_decode($request->getContent(), true);
    
    // Aquí puedes validar los datos recibidos y crear una nueva entidad Signin
    // a partir de los datos recibidos
    
    // Por ejemplo, si los datos recibidos son un arreglo asociativo, puedes crear una nueva entidad así:
    $signin = new Signin();
    $signin->setTimestart($data['timestart']);
    $signin->setTimerestart($data['timerestart']);
    $signin->setTimestop($data['timestop']);
    $signin->setTimefinish($data['timefinish']);
    $signin->setHourcount($data['hourcount']);

    $em = $doctrine->getManager();
    $em->persist($signin);
    $em->flush();

    return $this->json([
        'message' => 'Signin created successfully',
        'data' => [
            'id' => $signin->getId(),
            'timestart' => $signin->getTimestart(),
            'timerestart' => $signin->getTimerestart(),
            'timestop' => $signin->getTimestop(),
            'timefinish' => $signin->getTimefinish(),
            'hourcount' => $signin->getHourcount(),
        ]
    ]);
}

#[Route('/update/{id}', name: 'app_apisignin_update', methods: ['PUT', 'PATCH'])]
public function update(Request $request, Signin $signin, ManagerRegistry $doctrine): Response
{
    $data = json_decode($request->getContent(), true);
    
    // Aquí puedes validar los datos recibidos y actualizar la entidad Signin
    // correspondiente a partir de los datos recibidos
    
    // Por ejemplo, si los datos recibidos son un arreglo asociativo, puedes actualizar la entidad así:
    $signin->setTimestart($data['timestart']);
    $signin->setTimerestart($data['timerestart']);
    $signin->setTimestop($data['timestop']);
    $signin->setTimefinish($data['timefinish']);
    $signin->setHourcount($data['hourcount']);

    $em = $doctrine->getManager();
    $em->persist($signin);
    $em->flush();

    return $this->json([
        'message' => 'Signin updated successfully',
        'data' => [
            'id' => $signin->getId(),
            'timestart' => $signin->getTimestart(),
            'timerestart' => $signin->getTimerestart(),
            'timestop' => $signin->getTimestop(),
            'timefinish' => $signin->getTimefinish(),
            'hourcount' => $signin->getHourcount(),
        ]
    ]);
}


}
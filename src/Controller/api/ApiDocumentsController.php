<?php

namespace App\Controller\api;

use App\Entity\Documents;
use App\Form\DocumentsType;
use App\Repository\DocumentsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

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
                'docname' => $p->getDocname(),
                'file' => $this->generateUrl('app_apidocuments_file', ['id' => $p->getId()])
            ];
        }

        return $this->json($data, $status = 200, $headers = ['Access-Control-Allow-Origin'=>'*']);
    }

    #[Route('/file/{id}', name: 'app_apidocuments_file', methods: ['GET'])]
    public function getFile(Documents $document): Response
    {
        $filePath = $this->getParameter('documents_directory').'/'.$document->getFile();
        $fileContent = file_get_contents($filePath);
        $response = new Response($fileContent);
        $disposition = $response->headers->makeDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, $document->getDocname());
        $response->headers->set('Content-Disposition', $disposition);
        return $response;
    }

    #[Route('/create', name: 'app_apidocuments_create', methods: ['POST'])]
    public function create(Request $request, ManagerRegistry $doctrine, SluggerInterface $slugger): Response
    {
        $document = new Documents();
        $form = $this->createForm(DocumentsType::class, $document);
        $form->handleRequest($request);

        // $uploadedFile = $request->files->get('document');
        // if (!$uploadedFile) {
        //     return $this->json(['error' => 'No file uploaded'], 400);
        // }

        // // Store the file in a server directory
        // $destination = $this->getParameter('kernel.project_dir').'/public/uploads/documents';
        // $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
        // $safeFilename = $slugger->slug($originalFilename);
        // $newFilename = $safeFilename.'-'.uniqid().'.'.$uploadedFile->guessExtension();
        // $uploadedFile->move(
        //     $destination,
        //     $newFilename
        // );

        // // Do other operations with the submitted file data
        // $submittedDateTime = new \DateTime();
        // $data = [
        //     'fileName' => $newFilename,
        //     'submitTime' => $submittedDateTime->format('Y-m-d H:i:s'),
        // ];

        // return $this->json($data, 200);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $file */
            $file = $form->get('file')->getData();
            $fileName = uniqid().'.'.$file->guessExtension();

            try {
                $file->move(
                    $this->getParameter('documents_directory'),
                    $fileName
                );
            } catch (FileException $e) {
                return $this->json([
                    'message' => 'Error al cargar el archivo'
                ], $status = 400);
            }

            $document->setFile($fileName);

            $em = $doctrine->getManager();
            $em->persist($document);
            $em->flush();

            return $this->json([
                'message' => 'Documento subido correctamente'
            ], $status = 201, $headers = ['Access-Control-Allow-Origin'=>'*']);
        }

        return $this->json([
            'message' => 'Se ha producido un ERROR'
        ], $status = 400, $headers = ['Access-Control-Allow-Origin'=>'*']);
    }
}

<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileUploadController extends AbstractController
{
    /**
     * @Route("/api/fileUpload", name="api_file_upload", methods={"POST"})
     */
    public function upload(Request $request, SluggerInterface $slugger): Response
    {
        $uploadedFile = $request->files->get('file');
        if (!$uploadedFile) {
            return $this->json(['error' => 'No file uploaded'], 400);
        }

        // Store the file in a server directory
        $destination = $this->getParameter('kernel.project_dir').'/public/uploads/documents';
        $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $slugger->slug($originalFilename);
        $newFilename = $safeFilename.'-'.uniqid().'.'.$uploadedFile->guessExtension();
        $uploadedFile->move(
            $destination,
            $newFilename
        );

        // Do other operations with the submitted file data
        $submittedDateTime = new \DateTime();
        $data = [
            'fileName' => $newFilename,
            'submitTime' => $submittedDateTime->format('Y-m-d H:i:s'),
        ];

        return $this->json($data, 200);
    }
}
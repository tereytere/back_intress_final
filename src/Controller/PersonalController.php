<?php

namespace App\Controller;

use App\Entity\Personal;
use App\Form\PersonalType;
use App\Repository\PersonalRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/personal')]
class PersonalController extends AbstractController
{
    #[Route('/', name: 'app_personal_index', methods: ['GET'])]
    public function index(PersonalRepository $personalRepository): Response
    {
        return $this->render('personal/index.html.twig', [
            'personals' => $personalRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_personal_new', methods: ['GET', 'POST'])]
    public function new(Request $request, PersonalRepository $personalRepository, ManagerRegistry $doctrine, SluggerInterface $slugger): Response
    {
        $personal = new Personal();
        $form = $this->createForm(PersonalType::class, $personal);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $personalRepository->save($personal, true);

            $brochureFile = $form->get('image')->getData();
            if ($brochureFile) {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$brochureFile->guessExtension();

                try {
                    $brochureFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    throw new Exception('SORRY! Algo ha fallado');
                }

                $personal->setImage($newFilename);
            }
            $em = $doctrine->getManager();
            $em->persist($personal);
            $em->flush();

            return $this->redirectToRoute('app_personal_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('personal/new.html.twig', [
            'personal' => $personal,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_personal_show', methods: ['GET'])]
    public function show(Personal $personal): Response
    {
        return $this->render('personal/show.html.twig', [
            'personal' => $personal,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_personal_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, PersonalRepository $personalRepository, ManagerRegistry $doctrine, SluggerInterface $slugger): Response
    {
        $personal = new Personal();
        $form = $this->createForm(PersonalType::class, $personal);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $personalRepository->save($personal, true);

            $brochureFile = $form->get('image')->getData();
            if ($brochureFile) {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$brochureFile->guessExtension();

                try {
                    $brochureFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    throw new Exception('SORRY! Algo ha fallado');
                }

                $personal->setImage($newFilename);
            }
            $em = $doctrine->getManager();
            $em->persist($personal);
            $em->flush();

            return $this->redirectToRoute('app_personal_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('personal/edit.html.twig', [
            'personal' => $personal,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_personal_delete', methods: ['POST'])]
    public function delete(Request $request, Personal $personal, PersonalRepository $personalRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$personal->getId(), $request->request->get('_token'))) {
            $personalRepository->remove($personal, true);
        }

        return $this->redirectToRoute('app_personal_index', [], Response::HTTP_SEE_OTHER);
    }
}

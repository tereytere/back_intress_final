<?php

namespace App\Controller;

use App\Entity\Vacation;
use App\Form\VacationType;
use App\Repository\VacationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/vacation')]
class VacationController extends AbstractController
{
    #[Route('/', name: 'app_vacation_index', methods: ['GET'])]
    public function index(VacationRepository $vacationRepository): Response
    {
        return $this->render('vacation/index.html.twig', [
            'vacations' => $vacationRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_vacation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, VacationRepository $vacationRepository): Response
    {
        $vacation = new Vacation();
        $form = $this->createForm(VacationType::class, $vacation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $vacationRepository->save($vacation, true);

            return $this->redirectToRoute('app_vacation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('vacation/new.html.twig', [
            'vacation' => $vacation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_vacation_show', methods: ['GET'])]
    public function show(Vacation $vacation): Response
    {
        return $this->render('vacation/show.html.twig', [
            'vacation' => $vacation,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_vacation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Vacation $vacation, VacationRepository $vacationRepository): Response
    {
        $form = $this->createForm(VacationType::class, $vacation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $vacationRepository->save($vacation, true);

            return $this->redirectToRoute('app_vacation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('vacation/edit.html.twig', [
            'vacation' => $vacation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_vacation_delete', methods: ['POST'])]
    public function delete(Request $request, Vacation $vacation, VacationRepository $vacationRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$vacation->getId(), $request->request->get('_token'))) {
            $vacationRepository->remove($vacation, true);
        }

        return $this->redirectToRoute('app_vacation_index', [], Response::HTTP_SEE_OTHER);
    }
}

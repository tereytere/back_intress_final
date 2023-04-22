<?php

namespace App\Controller\api;

use App\Entity\Signing;
use App\Entity\Holidays;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/apivacation')]
class ApiVacationController extends AbstractController
{
    #[Route('/list', name: 'app_apivacation_index', methods: ['GET'])]
    public function calculateVacationDays($userId)
    {
        // Obtener los datos necesarios de la entidad Signing
        $signingRepo = $this->getDoctrine()->getRepository(Signing::class);
        $signedHours = $signingRepo->getTotalSignedHoursByUser($userId);

        // Obtener los datos necesarios de la entidad Holidays
        $holidaysRepo = $this->getDoctrine()->getRepository(Holidays::class);
        $accumulatedDays = $holidaysRepo->getAccumulatedDaysByUser($userId);

        // Calcular los días de vacaciones generados por mes
        $workingDaysPerMonth = 30; // Suponiendo un mes con 30 días
        $vacationDaysPerMonth = 2.5; // Suponiendo que son 2.5 días de vacaciones por mes trabajado
        $generatedDays = ($signedHours / ($workingDaysPerMonth * 8)) * $vacationDaysPerMonth;

        // Sumar los días generados a los días acumulados
        $totalDays = $generatedDays + $accumulatedDays;

        // Redondear los días de vacaciones generados y devolver el resultado como una respuesta JSON
        return new JsonResponse(['vacationDays' => round($totalDays)]);
    }
}
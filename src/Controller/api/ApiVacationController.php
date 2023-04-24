<?php

namespace App\Controller\api;

use App\Entity\Signin;
use App\Entity\Holidays;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/apivacation')]
class ApiVacationController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/list', name: 'app_apivacation_index', methods: ['POST'])]
    public function calculateVacationDays(Request $request)
    {
        // Obtener los datos necesarios de la entidad Signin
        $requestContent = json_decode($request->getContent(), true);
        $userId = $requestContent['user_id'];
        $signintotalhour = $requestContent['signin_totalHours'];

        // Obtener los datos necesarios de la entidad Holidays
        $holidaysRepo = $this->entityManager->getRepository(Holidays::class);
        $userHolidays = $holidaysRepo->findOneBy(['user' => $userId]);

        if (!$userHolidays) {
            // Si no existe el registro de vacaciones para el usuario, crear uno nuevo
            $userHolidays = new Holidays();
            $userHolidays->setUser($userId);
            $userHolidays->setDays(0.0);
        }

        // Calcular los días de vacaciones generados por mes
        $workingDaysPerMonth = 30; // Suponiendo un mes con 30 días
        $vacationDaysPerMonth = 2.5; // Suponiendo que son 2.5 días de vacaciones por mes trabajado
        $generatedDays = ($signintotalhour / ($workingDaysPerMonth * 8)) * $vacationDaysPerMonth;

        // Sumar los días generados a los días acumulados
        $userHolidays->setDays($userHolidays->getDays() + $generatedDays);

        // Guardar los cambios en la base de datos
        $this->entityManager->persist($userHolidays);
        $this->entityManager->flush();

        // Devolver el resultado como una respuesta JSON
        return new JsonResponse(['vacationDays' => round($generatedDays), 'totalVacationDays' => round($userHolidays->getDays())]);
    }
}
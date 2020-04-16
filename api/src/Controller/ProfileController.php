<?php

namespace App\Controller;

use App\Repository\VisitRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/profile", name="profile_")
 */
class ProfileController extends AbstractController
{
    /**
     * @IsGranted("ROLE_USER")
     * @Route("/visits", name="visits")
     */
    public function getVisits(VisitRepository $visitRepository)
    {
        $user = $this->getUser();

        $visits = $visitRepository->findBy(['visitor' => $user]);

        return new JsonResponse($visits);
    }
}

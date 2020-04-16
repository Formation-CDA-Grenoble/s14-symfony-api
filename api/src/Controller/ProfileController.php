<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Visit;
use App\Repository\VisitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @IsGranted("ROLE_USER")
 * @Route("/profile", name="profile_")
 */
class ProfileController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/visits", name="visits")
     */
    public function getVisits(VisitRepository $visitRepository)
    {
        $user = $this->getUser();

        $visits = $visitRepository->findBy(['visitor' => $user]);

        return new JsonResponse($visits);
    }

    /**
     * @Route("/visit/{id<\d+>}", methods={"POST"}, name="create-visit")
     */
    public function createVisit(User $visited)
    {
        $visitor = $this->getUser();

        $visit = new Visit();

        $visit->setVisitor($visitor);
        $visit->setVisited($visited);

        $this->entityManager->persist($visit);
        $this->entityManager->flush();

        return new JsonResponse($visit);
    }
}

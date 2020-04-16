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
    private $visitRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        VisitRepository $visitRepository
    )
    {
        $this->entityManager = $entityManager;
        $this->visitRepository = $visitRepository;
    }

    /**
     * @Route("/visits", name="visits")
     */
    public function getVisits()
    {
        $user = $this->getUser();

        $visits = $this->visitRepository->findBy(['visitor' => $user]);

        return new JsonResponse($visits);
    }

    /**
     * @Route("/visit/{id<\d+>}", methods={"POST"}, name="create-visit")
     */
    public function createVisit(User $visited)
    {
        $visitor = $this->getUser();

        $existingVisit = $this->visitRepository->findOneBy([
            'visitor' => $visitor,
            'visited' => $visited,
        ]);

        if ($existingVisit === null) {
            $visit = new Visit();

            $visit->setVisitor($visitor);
            $visit->setVisited($visited);

            $this->entityManager->persist($visit);
            $this->entityManager->flush();

            return new JsonResponse($visit);
        } else {
            return new JsonResponse($existingVisit);
        }
    }
}

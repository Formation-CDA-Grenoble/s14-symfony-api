<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Visit;
use App\Repository\UserRepository;
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
        UserRepository $userRepository,
        VisitRepository $visitRepository
    )
    {
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
        $this->visitRepository = $visitRepository;
    }

    /**
     * @Route("/visits", name="visits")
     */
    public function getVisits()
    {
        $user = $this->getUser();

        $visits = $user->getSentVisits();

        return new JsonResponse($visits->getValues());
    }

    /**
     * @Route("/visitors", name="visitors")
     */
    public function getVisitors()
    {
        $visited = $this->getUser();

        $visitors = $this->userRepository->getVisitors($visited);

        return new JsonResponse($visitors);
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

<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Heart;
use App\Entity\Visit;
use App\Repository\UserRepository;
use App\Repository\HeartRepository;
use App\Repository\VisitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * @IsGranted("ROLE_USER")
 * @Route("/profile", name="profile_")
 */
class ProfileController extends AbstractController
{
    private $entityManager;
    private $heartRepository;
    private $userRepository;
    private $visitRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        HeartRepository $heartRepository,
        UserRepository $userRepository,
        VisitRepository $visitRepository
    )
    {
        $this->entityManager = $entityManager;
        $this->heartRepository = $heartRepository;
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

            return new JsonResponse($visit, JsonResponse::HTTP_CREATED);
        } else {
            return new JsonResponse($existingVisit);
        }
    }

    /**
     * @Route("/heart/{id<\d+>}", methods={"GET"}, name="get-latest-heart")
     */
    public function getLatestHeart(User $user)
    {
        $currentUser = $this->getUser();

        $latestHeart = $this->heartRepository->findOneBy([
            'sender' => $currentUser,
            'recipient' => $user,
        ], ['createdAt' => 'DESC']);

        if (is_null($latestHeart)) {
            return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
        }

        return new JsonResponse($latestHeart);
    }

    /**
     * @Route("/heart/{id<\d+>}", methods={"POST"}, name="send-heart")
     */
    public function sendHeart(User $user)
    {
        $currentUser = $this->getUser();

        $latestHeart = $this->heartRepository->findOneBy([
            'sender' => $currentUser,
            'recipient' => $user,
        ], ['createdAt' => 'DESC']);

        if (!is_null($latestHeart)) {
            $interval = \date_diff($latestHeart->getCreatedAt(), new \DateTime());

            if ($interval->days < 1) {
                throw new AccessDeniedHttpException('User not allowed to send multiple hearts within 24 hours.');
            }
        }

        $heart = new Heart();

        $heart->setSender($currentUser);
        $heart->setRecipient($user);

        $this->entityManager->persist($heart);
        $this->entityManager->flush();

        return new JsonResponse($heart, JsonResponse::HTTP_CREATED);
    }
}

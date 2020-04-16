<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/user", name="user_")
 */
class UserController extends AbstractController
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @Route("/", name="get-all")
     */
    public function getAll(): JsonResponse
    {
        $users = $this->userRepository->findAll();

        return new JsonResponse($users);
    }

    /**
     * @Route("/{id<\d+>}", name="get-by-id")
     */
    public function getById(User $user): JsonResponse
    {
        return new JsonResponse($user);
    }
}

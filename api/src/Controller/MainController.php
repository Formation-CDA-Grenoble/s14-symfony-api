<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function home()
    {
        return $this->json([
            'message' => 'Vous n\'avez pas besoin d\'être authentifié pour accéder à cette page!'
        ]);
    }

    /**
     * @IsGranted("ROLE_USER")
     * @Route("/test", name="test")
     */
    public function test()
    {
        return $this->json([
            'message' => 'Vous êtes authentifié!'
        ]);
    }
}

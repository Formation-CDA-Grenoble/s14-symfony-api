<?php

namespace App\Controller;

use App\Repository\CityRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/city", name="city_")
 */
class CityController extends AbstractController
{
    private $cityRepository;

    public function __construct(CityRepository $cityRepository)
    {
        $this->cityRepository = $cityRepository;
    }

    /**
     * @Route("/", methods={"GET"}, name="get-all")
     */
    public function getAll()
    {
        $cities = $this->cityRepository->findAll();

        return new JsonResponse($cities);
    }
}

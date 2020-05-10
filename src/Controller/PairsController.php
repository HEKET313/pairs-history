<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class FilterController
 * @package App\Controller
 */
class PairsController extends AbstractController
{
    /**
     * @return Response
     * @Route("")
     */
    public function index(): Response
    {
        return new Response("111");
    }
}

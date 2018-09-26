<?php

namespace App\Controller;

use App\Entity\Teams;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    /**
     * @Route("/test", name="test")
     */
    public function index()
    {
        $teams = $this->getDoctrine()->getRepository(Teams::class)->findAll();
        return $this->render('test/index.html.twig', [
            'controller_name' => 'TestController',
            'teams' => $teams,
        ]);
    }
}

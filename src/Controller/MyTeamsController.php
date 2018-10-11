<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MyTeamsController extends AbstractController
{
    /**
     * @Route("/my/teams", name="my_teams")
     */
    public function index()
    {
        return $this->render('my_teams/index.html.twig', [
            'controller_name' => 'MyTeamsController',
        ]);
    }
}

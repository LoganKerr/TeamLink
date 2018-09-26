<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class JoinTeamController extends AbstractController
{
    /**
     * @Route("/join/team", name="join_team")
     */
    public function index()
    {
        return $this->render('join_team/index.html.twig', [
            'controller_name' => 'JoinTeamController',
        ]);
    }
}

<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CreateTeamController extends AbstractController
{
    /**
     * @Route("/create/team", name="create_team")
     */
    public function index()
    {
        return $this->render('create_team/index.html.twig', [
            'controller_name' => 'CreateTeamController',
        ]);
    }
}

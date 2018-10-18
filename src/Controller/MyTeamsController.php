<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MyTeamsController extends AbstractController
{
    /**
     * @Route("/myteams", name="myteams")
     */
    public function index()
    {
        return $this->render('my_teams/index.html.twig', [
            'controller_name' => 'MyTeamsController',
            'nav' => array(
                'page' => 'myteams'
            )
        ]);
    }
}

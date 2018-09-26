<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class EditTeamController extends AbstractController
{
    /**
     * @Route("/edit/team", name="edit_team")
     */
    public function index()
    {
        return $this->render('edit_team/index.html.twig', [
            'controller_name' => 'EditTeamController',
        ]);
    }
}

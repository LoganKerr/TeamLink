<?php

namespace App\Controller;

use App\Entity\Users;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use App\Entity\Teams;

class LoginController extends AbstractController
{
    public $user_id = -1;
    /**
     * @Route("/dfgdfgdf", name="dfhgfhgf")
     */
    public function index()
    {
        $request = Request::createFromGlobals();
        $request_method = $request->getMethod();
        if ($request_method == "POST")
        {
            if(self::checkLogin($request, $user_id))
            {
                dump($user_id);
                self::login($user_id);
            }
        }
        dump($this->get('session')->get('user_id'));
        /*
        if ($this->get('session')->get('id'))
        {
            return $this->redirect($this->generateUrl('menu'));
        }
        */
        $error = array(
        );
        $email = "";
        return $this->render('login/index.html.twig', [
            'controller_name' => 'LoginController',
            'error' => $error,
            'email' => $email,
        ]);
    }
    public function checkLogin($request, $user_id) : Boolean
    {
        // TODO: THROW ERROR IF REQUIRED FIELDS ARE EMPTY
        $email = $request->request->get('email');
        $pass = $request->request->get('pass');
        if (!empty($email) && !empty($pass))
        {
            // find user by email
            $user = $this->getDoctrine()->getRepository(Users::class)->findOneBy([
                'email' => $email,
            ]);
            // no user with that email found
            if (count($user) == 0)
            {
                return false;

            }
            else {
                $passhash = $user->getpasshash();
                // if inputted password matches user in db
                if (password_verify($pass, $passhash))
                {
                    $user_id = $user->getId();
                    dump($user_id);
                    return true;
                }
            }
        }
        return false;
    }

    // sets session
    public function login($user_id)
    {
        $this->get('session')->set('user_id', $user_id);
    }
}

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
    /**
     * @Route("/login", name="login")
     */
    public function index()
    {
        $session = new Session();
        $user_id = -1;
        $request = Request::createFromGlobals();
        $request_method = $request->getMethod();
        if ($request_method == "POST")
        {
            if(self::checkLogin($request))
            {
                self::login($user_id, $session);
                dump($session);
            }
        }
        if ($session.get('id'))
        {
            header("Location: menu.php");
        }
        $error = array(
        );
        $email = "";
        return $this->render('login/index.html.twig', [
            'controller_name' => 'LoginController',
            'error' => $error,
            'email' => $email,
        ]);
    }
    public function checkLogin($request)
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
                return -1;

            }
            else {
                $passhash = $user->getpasshash();
                // if inputted password matches user in db
                if (password_verify($pass, $passhash))
                {
                    $user_id = $user->getId();
                    return true;
                }
            }
        }
        return -1;
    }

    public function login($user_id, $session)
    {
        $session->set('id', '$user_id');
    }
}

<?php

namespace App\Controller;

use App\Entity\Faculty;
use App\Entity\Student;
use App\Entity\University;
use App\Entity\User;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SignupController extends AbstractController
{
    /**
     * @Route("/register", name="register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        // 1) build the form
        $user = new User();
        $universities = $this->getDoctrine()->getRepository(University::class)->findAll();

        $form = $this->createForm(UserType::class, $user, array(
            'universities' => $universities
        ));

        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $role = $form['role']->getData();
            $major = $form['major']->getData();
            $interests = $form['interests']->getData();
            $department = $form['department']->getData();
            $university = $form['university']->getData();
            $universityId = $university->getId();

            $entityManager = $this->getDoctrine()->getManager();

            // if university id submitted exists
            if($this->getDoctrine()->getRepository(University::class)->findOneBy(['id' => $universityId]))
            {
                $user->setUniversityId($universityId);
            }

            if ($role == "Student") {
                $student = new Student();
                $student->setMajor($major);
                $student->setInterests($interests);
                $entityManager->persist($student);
                $entityManager->flush();
                $student_id = $student->getId();
                $user->setStudentId($student_id);
            }
            else
            {
                $faculty = new Faculty();
                $faculty->setDepartment($department)
                $entityManager->persist($faculty);
                $entityManager->flush();
                $faculty_id = $faculty->getId();
                $user->setFacultyId($faculty_id);
            }

            // 3) Encode the password (you could also do this via Doctrine listener)
            $password = $passwordEncoder->encodePassword($user, $user->getPassword());
            $user->setPassword($password);

            // 4) save the User!
            $entityManager->persist($user);
            $entityManager->flush();

            // ... do any other work - like sending them an email, etc
            // maybe set a "flash" success message for the user

            return $this->redirectToRoute('index');
        }

        return $this->render(
            'signup/index.html.twig',
            array('form' => $form->createView())
        );
    }
}

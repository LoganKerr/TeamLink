<?php

namespace App\Controller;

use App\Entity\Faculty;
use App\Entity\Student;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Form\ProfileType;
use App\Entity\User;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class ProfileController extends AbstractController
{
    /**
     * @Route("/profile", name="profile")
     */
    public function index(Request $request)
    {
        // 1) build the form
        // get user from database
        $user = $this->getUser();
        $user_id = $user->getId();

        $sql = "SELECT `student_id`, `faculty_id`, `major`, `interests`, `department` FROM `user` LEFT JOIN `faculty` ON `user`.`faculty_id`=`faculty`.`id` LEFT JOIN `student` ON `user`.`student_id`=`student`.`id` WHERE `user`.`id`=:user LIMIT 1";
        $em = $this->getDoctrine()->getManager();
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->bindValue(":user",$user_id);
        $stmt->execute();
        $result = $stmt->fetchAll();

        $userData = $result[0];

        $form = $this->createForm(profileType::class, $user, array(
            'studentData' => array(
                'student_id' => $userData['student_id'],
                'major' => $userData['major'],
                'interests' => $userData['interests']
            ),
            'facultyData' => array(
                'faculty_id' => $userData['faculty_id'],
                'department' => $userData['department']
            )
        ));

        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();


            if ($userData['student_id']) {
                $major = $form['major']->getData();
                $interests = $form['interests']->getData();
                $student = $this->getDoctrine()->getRepository(Student::class)->findOneBy(['id' => $userData['student_id']]);
                $student->setMajor($major);
                $student->setInterests($interests);
                $entityManager->persist($student);
                $entityManager->flush();
            }
            else if ($userData['faculty_id'])
            {
                $department = $form['department']->getData();
                $faculty = $this->getDoctrine()->getRepository(Faculty::class)->findOneBy(['id' => $userData['faculty_id']]);
                $faculty->setDepartment($department);
                $entityManager->persist($faculty);
                $entityManager->flush();
            }

            // 4) save the User!
            $entityManager->persist($user);
            $entityManager->flush();

            // ... do any other work - like sending them an email, etc
            // maybe set a "flash" success message for the user

            //return $this->redirectToRoute('index');
        }

        return $this->render('profile/index.html.twig', [
            'controller_name' => 'ProfileController',
            'nav' => array(
                'page' => 'menu'
            ),
            'student_id' => $userData['student_id'],
            'faculty_id' => $userData['faculty_id'],
            'form' => $form->createView()
        ]);
    }
}

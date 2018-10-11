<?php

// src/Form/UserType.php
namespace App\Form;

use App\Entity\University;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class ProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $studentData = $options['studentData'];
        dump($studentData);
        $facultyData = $options['facultyData'];
        dump($facultyData);

        if ($studentData['student_id'])
        {
            $builder
                ->add('major',TextType::class, array('mapped' => false, 'data' => $studentData['major']))
                ->add('interests',TextType::class, array('mapped' => false, 'data' => $studentData['interests']));
        }
        else if ($facultyData['faculty_id']) {
            $builder
                ->add('department', TextType::class, array('mapped' => false, 'data' => $facultyData['department']));
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => User::class,
            'studentData' => null,
            'facultyData' => null
        ));
    }
}
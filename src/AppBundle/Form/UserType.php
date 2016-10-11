<?php

namespace AppBundle\Form;

use AppBundle\Entity\Countries;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;


class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('firstName', TextType::class)
            ->add('lastName', TextType::class)
            ->add('country', EntityType::class, [
                'placeholder' => 'Choose your country',
                'class' => 'AppBundle\Entity\Countries',
                'attr' => [
                    'class' => 'country-watcher'
                ],
                'choice_label' => 'name',
            ])
            ->add('city', EntityType::class, [
                'choice_attr' => [
                    'class' => 'text-muted'
                ],
                'placeholder' => 'Choose your city',
                'attr' => [
                    'class' => 'city-watcher',
                    'disabled' => true

                ],
                'class' => 'AppBundle\Entity\Cities',
                'choice_label' => 'name',
                'group_by' => function ($val, $key, $index) {
                }
            ])
            ->add('email', EmailType::class)
            ->add('plainPassword', RepeatedType::class, array(
                'type' => PasswordType::class,
                'first_options' => array('label' => 'Password'),
                'second_options' => array('label' => 'Repeat Password'),
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\User'
        ]);
    }

    public function getName()
    {
        return 'app_bundle_user_type';
    }
}

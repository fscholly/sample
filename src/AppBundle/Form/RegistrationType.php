<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use FOS\UserBundle\Util\LegacyFormHelper;


class RegistrationType extends AbstractType
{
   
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', TextType::class, array(
                'label' => 'Prénom ',
            ))              
            ->add('lastname', TextType::class, array(
                'label' => 'Nom',

            ))
            ->add('email', EmailType::class, array(
                'label' => 'Adresse Email', 
            ))
            ->add('username', TextType::class, array(
                'label' => 'Login', 
                'translation_domain' => 'FOSUserBundle',
            )) 
            ->add('plainPassword', LegacyFormHelper::getType('Symfony\Component\Form\Extension\Core\Type\RepeatedType'), array(
                'type' => LegacyFormHelper::getType('Symfony\Component\Form\Extension\Core\Type\PasswordType'),
                'options' => array('translation_domain' => 'FOSUserBundle'),
                'first_options' => array(
                    'label' => 'form.password',
                ),
                'second_options' => array(
                    'label' => 'form.password_confirmation',
                ),
                'invalid_message' => 'fos_user.password.mismatch',
            )) 
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\User',
            'cascade_validation' => true,
        ));
    }

   
    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\RegistrationFormType';

        // Or for Symfony < 2.8
        // return 'fos_user_registration';
    }

    public function getBlockPrefix()
    {
        return 'app_user_registration';
    }

    // For Symfony 2.x
    public function getName()
    {
        return $this->getBlockPrefix();
    }
}

<?php

namespace AppBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

use AppBundle\Form\PhotoableType;

use FOS\UserBundle\Util\LegacyFormHelper;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;

class ProfileType extends PhotoableType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        
        if (array_key_exists('user', $options)) {
            $user = $options['user'];
        }
        else {
            $user = null;
        }
        
        $builder
            ->add('username', HiddenType::class)
            ->add('firstname', TextType::class, array(
                'label' => 'Prénom',
                'required' => false,
            ))
            ->add('lastname', TextType::class, array(
                'label' => 'Nom',
                'required' => false,
            ))
            ->add('email', TextType::class, array(
                'label' => 'E-mail',
            ))
            ->add('current_password', LegacyFormHelper::getType('Symfony\Component\Form\Extension\Core\Type\PasswordType'), array(
                'label' => 'Mot de passe actuel',
                'translation_domain' => 'FOSUserBundle',
                'mapped' => false,
                'required' => true,
                'constraints' => new UserPassword(),
            ))
        ;
    }

    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\ProfileFormType';
    }

    public function getBlockPrefix()
    {
        return 'app_user_profile';
    }

    // For Symfony 2.x
    public function getName()
    {
        return $this->getBlockPrefix();
    }
    
}


<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class PhotoType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $forEntity = $options['for_entity'];
        if (!empty($forEntity) && $forEntity == 'product') {
            $builder
            ->add('main', CheckboxType::class, array(
                'mapped' => false,
                'label'    => 'Image principale',
                'label_format' => 'checkbox',
                'required' => false,
                'data' => $options['is_main_photo'],
            ))
            ->add('file',FileType::class, array(
            'label'=>' ',
            'required' => false,
            'attr' => array('class' => 'simplefilepreview'),
            ))
            ->add('updatedAt',DateType::class,array(
            'widget' => 'single_text',
            'required' => false,
            'format' => 'dd/MM/yyyy hh:mm:ss',
            'data' => new \DateTime(),
            ))
            ;
        }
        else{
            $builder
            ->add('file',FileType::class, array(
                'label'=>' ',
                'required' => false,
                'attr' => array('class' => 'cropit-image-input'),
            ))
            ->add('imageData', HiddenType::class, array(
                'attr' => array('class' => 'hidden-image-data'),
            ))
            ->add('updatedAt',DateType::class,array(
                'widget' => 'single_text',
                'required' => false,
                'format' => 'dd/MM/yyyy hh:mm:ss',
                'data' => new \DateTime(),
            ))
            ; 
        }
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Photo',
            'attr' => array('id' => 'form_product_photo'),
            'for_entity' => null,
            'is_main_photo' => false,
        ));
    }
}

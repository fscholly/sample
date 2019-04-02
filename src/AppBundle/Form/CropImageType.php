<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class CropImageType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('file',FileType::class, array(
                'label'=>' ',
                'required' => false,
                'attr' => array('class' => 'cropit-image-input'),
            ))
            ->add('imageData', HiddenType::class, array(
                'attr' => array('class' => 'hidden-image-data'),
            ))
            ->add('imageType',EntityType::class, array(
                'label'=>'Type d\'image',
                'class' => 'AppBundle:ImageType',
                'required' => false,
                'attr' => array('class' => 'select2'),
            ))
            ->add('updatedAt',DateType::class,array(
                'widget' => 'single_text',
                'required' => false,
                'format' => 'dd/MM/yyyy hh:mm:ss',
                'data' => new \DateTime(),
            ))
        ;
        
        
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Image',
            'attr' => array('id' => 'form_image'),
        ));
    }
}

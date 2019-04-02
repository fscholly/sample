<?php
namespace AppBundle\Form;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\Date;

use AppBundle\Form\CropImageType;
use AppBundle\Entity\ConfigOption;

class CompanyConfigType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // traitement supplémentaire pour la page 'options' 
        if (true == $options['appLogo']) {
            $builder
                ->add('appLogo', CropImageType::class, array(
                    'required' => false,
                ))
            ;
        }
        if (true == $options['quotationLogo']) {
            $builder
                ->add('quotationLogo', CropImageType::class, array(
                    'required' => false,
                ))
            ;
        }
        
        // construction du formulaire à partir des options de la configuration (passés en paramètres)
        $configOptionValues = $options['configOptionValues'];
        if ($configOptionValues) {
            foreach ($configOptionValues as $optionValue) {
                $configOption = $optionValue->getConfigOption();
                $type = $configOption->getType();

                switch($type){
                    case ConfigOption::OPTION_TEXT: 
                    case ConfigOption::OPTION_TEXT_REQUIRED:
                        $required = $type == ConfigOption::OPTION_TEXT_REQUIRED ? true : false;
                        $data = $optionValue->getValue();
                        $this->buildFormText($builder, $configOption, $optionValue, $required);
                        break;

                    case ConfigOption::OPTION_CHOICE:
                    case ConfigOption::OPTION_CHOICE_REQUIRED:
                        $required = $type == ConfigOption::OPTION_CHOICE_REQUIRED ? true : false;
                        $this->buildFormChoices($builder, $configOption, $optionValue, $required);
                        break;

                    case ConfigOption::OPTION_CHECKBOX:
                        $this->buildFormCheckbox($builder, $configOption, $optionValue);
                        break;

                    case ConfigOption::OPTION_TEXTAREA: 
                    case ConfigOption::OPTION_TEXTAREA_REQUIRED:
                        $required = $type == ConfigOption::OPTION_TEXTAREA_REQUIRED ? true : false;
                        $class = "materialnote-simple";
                        $data = $optionValue->getValue();
                        $this->buildFormText($builder, $configOption, $optionValue, $required, $class);
                        break;
                    
                    case ConfigOption::OPTION_NUMBER: 
                    case ConfigOption::OPTION_NUMBER_REQUIRED:
                        $required = $type == ConfigOption::OPTION_NUMBER_REQUIRED ? true : false;
                        $data = $optionValue->getValue();
                        $this->buildFormNumber($builder, $configOption, $optionValue, $required);
                        break;
                    
                    case ConfigOption::OPTION_DATE: 
                    case ConfigOption::OPTION_DATE_REQUIRED:
                        $required = $type == ConfigOption::OPTION_NUMBER_REQUIRED ? true : false;
                        $class = "pikaday";
                        $data = $optionValue->getValue();
                        $this->buildFormDate($builder, $configOption, $optionValue, $required, $class);
                        break;
                    default:
                        break;
                }
            }
        }
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => null,
            'configOptionValues' => null,
            'appLogo' => false,
            'quotationLogo' => false,
        ));
    }
    
    public function buildFormText($builder, $configOption, $optionValue, $required, $class = null)
    {
        $label = $configOption->getName();
        $code = $configOption->getCode();
        $attr = array (
            'family' => $configOption->getOptionFamily()->getCode(),
            'code' => $configOption->getCode(),
            'class' => $class,
        );
        
        $builder->add(strtolower($code), TextType::class, array(
            'label' => $label,
            'required' => $required,
            'attr' => $attr, 
            'constraints' => ($required ? new NotBlank() : null),
            'data' => $optionValue->getValue(),
        ));
    }
    
    public function buildFormChoices($builder, $configOption, $optionValue, $required)
    {
        $label = $configOption->getName();
        $code = $configOption->getCode();
        $optionChoices = $configOption->getOptionChoices();
        $optionChoice = $optionValue->getOptionChoice();
        $attr = array (
            'family' => $configOption->getOptionFamily()->getCode(),
            'code' => $configOption->getCode(),
            'class' => 'select2',
        );
        
        if ($required) {
            $attr['required'] = 'required';
        }
        
        $builder->add(strtolower($code), EntityType::class, array(
            'label' => $label,
            'class' => 'AppBundle:OptionChoice',
            'required' => $required,
            'attr' => $attr,
            'constraints' => ($required ? new NotNull() : null),
            'choices' => $optionChoices,
            'data' => $optionChoice,
        ));
    }
    
    public function buildFormCheckbox($builder, $configOption, $optionValue)
    {
        $label = $configOption->getName();
        $code = $configOption->getCode();
        $value = $optionValue->getValue() ? true : false;
        $attr = array (
            'family' => $configOption->getOptionFamily()->getCode(),
            'code' => $configOption->getCode(),
        );
        
        $builder->add(strtolower($code), CheckboxType::class, array(
            'label'    => $label,
            'label_format' => 'checkbox',
            'label_attr' => array('class' => 'hidden'),
            'required' => false,
            'attr' => $attr, 
            'data' => $value,
        ));

    }
    
    public function buildFormNumber($builder, $configOption, $optionValue, $required)
    {
        $label = $configOption->getName();
        $code = $configOption->getCode();
        $attr = array (
            'family' => $configOption->getOptionFamily()->getCode(),
            'code' => $configOption->getCode(),
        );
        $constraints = array(new Type (array("type" =>"float")));
        if ($required) {
            $constraints[] =  new NotBlank();
        }
        
        $builder->add(strtolower($code), NumberType::class, array(
            'label' => $label,
            'required' => $required,
            'attr' => $attr, 
            'constraints' => $constraints,
            'data' => floatval($optionValue->getValue()),
        ));
    }
    
    public function buildFormDate($builder, $configOption, $optionValue, $required, $class = null)
    {
        $label = $configOption->getName();
        $code = $configOption->getCode();
        $attr = array (
            'family' => $configOption->getOptionFamily()->getCode(),
            'code' => $configOption->getCode(),
            'class' => $class,
        );
        
        $value = $optionValue->getValue();
        $data = $value ? \DateTime::createFromFormat("d/m/Y",$value) : null;
        $constraints = array(new Date());
        if ($required) {
            $constraints[] =  new NotBlank();
        }
        
        $builder->add(strtolower($code), DateType::class, array(
            'widget' => 'single_text',
            'label' => $label,
            'required' => $required,
            'attr' => $attr, 
            'empty_data' => "",
            'format' => 'dd/MM/yyyy',
            'constraints' => $constraints,
            'data' => $data,
        ));
    }
}
<?php

namespace AppBundle\Services;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

use \AppBundle\Entity\ConfigOption;

class ConfigHandler extends \Twig_Extension implements \Twig_Extension_GlobalsInterface
{
    protected $em;
    protected $tokenStorage;
    protected $authorizationChecker;

    public function __construct(EntityManager $em, TokenStorageInterface $tokenStorage, AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->em = $em;
        $this->tokenStorage = $tokenStorage;
        $this->authorizationChecker = $authorizationChecker;
    }

    /**
     * Retourne la configuration actuelle (celle de l'utilisateur connecté).
     */
    public function getConfig()
    {
        $companyConfig = $this->em->getRepository('AppBundle:CompanyConfig')->findCurrentConfig();

        return $companyConfig;
    }


    /**
     * Retourne la valeur d'une option de la configuration de la société à partir du code.
     *
     * $param string        $code  Code de l'option
     *
     * @return int | string $value Valeur de l'option de la configuration de la société
     */
    public function getConfigOption($code)
    {
        $value = null;
        if ($this->authorizationChecker->isGranted('IS_AUTHENTICATED_FULLY')) {
            $companyConfig = $this->getConfig();

            $optionValue = $this->em->getRepository('AppBundle:OptionValue')
                    ->findByCompanyConfigAndCode($companyConfig, $code);

            if ($optionValue) {
                $value = $optionValue->__toString();
            }
        }

        return $value;
    }

    public function isAdministrator($user)
    {

        $administrators = $this->getConfig()->getAdministrators();

        if ($administrators->contains($this)) {
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * On définit la liste des variables globales utilisables via twig.
     */
    public function getGlobals()
    {
        return array(
            'config' => $this->getConfig(),
        );
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('configOption', array($this, 'getConfigOption')),
            new \Twig_SimpleFunction('isAdministrator', array($this, 'isAdministrator')),
        );
    }

    // Créer les options par défault de la configuration
    public function setDefaultConfigOptions()
    {
        $companyConfig = $this->getConfig();
        $defaultOptionCodes = self::defaultOptionCodes();
        foreach ($defaultOptionCodes as $code => $value) {
            $option = $this->em->getRepository('AppBundle:ConfigOption')->findOneByCode($code);
            if ($option) {
                $optionValue = new \AppBundle\Entity\OptionValue();
                $optionValue->setCompanyConfig($companyConfig);
                $optionValue->setConfigOption($option);

                switch ($option->getType()) {
                    case ConfigOption::OPTION_TEXT: 
                    case ConfigOption::OPTION_TEXT_REQUIRED:
                    case ConfigOption::OPTION_TEXTAREA: 
                    case ConfigOption::OPTION_TEXTAREA_REQUIRED:
                    case ConfigOption::OPTION_CHECKBOX:
                        $optionValue->setValue($value);
                        $this->em->persist($optionValue);
                        break;

                    case ConfigOption::OPTION_CHOICE:
                    case ConfigOption::OPTION_CHOICE_REQUIRED: 
                        $optionChoice = $this->em->getRepository('AppBundle:OptionChoice')->findOneBy(array('value' => $value, 'configOption' => $option));
                        if ($optionChoice) {
                            $optionValue->setOptionChoice($optionChoice);
                        }
                        $this->em->persist($optionValue);
                        break;

                    default:
                        break;
                }
            }
        }
        $this->em->flush();
    }
    
    // Enregistrer les modifications effectuées sur les options de la configuration
    public function saveConfigOptions($configOptionValues, $datas)
    {
        $config = $this->getConfig();
        
        // Récupération de la modification du appLogo
        if (isset($datas['appLogo']) && !empty($config)) {
            $appLogo = $config->getAppLogo();
            $appLogo->setImageData($datas['appLogo']->getImageData());
            $appLogo->setUpdatedAt($datas['appLogo']->getUpdatedAt());
            $config->setAppLogo($appLogo);
            $this->em->persist($config);
        }
        
        // Récupération de la modification du logo facture
        if (isset($datas['quotationLogo']) && !empty($config)) {
            $quotationLogo = $config->getQuotationLogo();
            $quotationLogo->setImageData($datas['quotationLogo']->getImageData());
            $quotationLogo->setUpdatedAt($datas['quotationLogo']->getUpdatedAt());
            $config->setQuotationLogo($quotationLogo);
            $this->em->persist($config);
        }
        
        // Sauvegarde des options ConfigValue
        foreach ($configOptionValues as $optionValue) {
            $configOption = $optionValue->getConfigOption();
            $type = $configOption->getType();

            $code = $configOption->getCode();
            $formLabel = strtolower($code);

            // Récupérer les modifications pour chaque options
            switch ($type) {
                case ConfigOption::OPTION_TEXT: 
                case ConfigOption::OPTION_TEXT_REQUIRED:
                case ConfigOption::OPTION_TEXTAREA: 
                case ConfigOption::OPTION_TEXTAREA_REQUIRED:
                    $optionValue->setValue($datas[$formLabel]);
                    $this->em->persist($optionValue);
                    break;

                case ConfigOption::OPTION_CHOICE:
                case ConfigOption::OPTION_CHOICE_REQUIRED:
                    $optionValue->setOptionChoice($datas[$formLabel]);
                    $this->em->persist($optionValue);
                    break;

                case ConfigOption::OPTION_CHECKBOX:
                    $value = $datas[$formLabel] ? 1 : 0;
                    $optionValue->setValue($value);
                    $this->em->persist($optionValue);
                    break;

                default:
                    break;
            }
        }
        // Enregistrer les modification
        $this->em->flush();
    }
    
    
    public static function defaultOptionCodes()
    {
        return array(
            // Thème
            'THEME_COLOR' => 'indigo',
            'SIDEBAR_COLOR' => 'light',
            'NAVBAR_COLOR' => 'light',
            
            // Paramètres généraux
            'TVA_RATE' => "20",
            'SITENAME' => "NSDH",
            'SITESUBNAME' => "La gestion jusqu'au bout des doigts",
            'ADMINNAME' => "SCHFRAXX",
            'ADMINMAIL' => "francois@des-heros.com",
            'COMPANYNAME' => "Nous sommes des héros",
            'COMPANYWEBSITE' => "des-heros.com",
            'VERSION' => "0.2",
            'DESCRIPTION' => "Allez viens, on est bien...",
        );
    }
    
    // La méthode getName() identifie votre extension Twig, elle est obligatoire
    public function getName()
    {
        return 'ConfigHandler';
    }
}

<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

use AppBundle\Entity\ConfigOption;

class LoadConfigOptionData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * HOW TO:  ajouter des options à la configuration
     * 
     * 1. Ajouter une entrée à $options -> tableau (code, name, family, type)
     * 2. si c'est une nouvelle famille : Ajouter une entrée dans $families dans LoadOptionFamilyData.php 
     * 3. s'il s'agit d'un choix : Ajouter une entrée dans $choices dans LoadOptionChoiceData.php
     * 4. Penser à définir l'option par défaut 
     * 5. Ajouter une entrée au tableau retourné par la méthode CompanyConfig::defaultOptionCodes()
     * 
     * 5. Recharger les fixtures
     */
    
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
       
        $options = array (
            // Paramètres généraux
            array('code' => 'TVA_RATE', 'name' => 'TVA appliquable', 'family' => 'GLOBAL', 'type' => ConfigOption::OPTION_CHOICE),
            array('code' => 'SITENAME', 'name' => 'Nom du site', 'family' => 'GLOBAL', 'type' => ConfigOption::OPTION_TEXT_REQUIRED),
            array('code' => 'SITESUBNAME', 'name' => 'Sous-titre du titre', 'family' => 'GLOBAL', 'type' => ConfigOption::OPTION_TEXT_REQUIRED),
            array('code' => 'ADMINNAME', 'name' => 'Nom de l\'administrateur', 'family' => 'GLOBAL', 'type' => ConfigOption::OPTION_TEXT_REQUIRED),
            array('code' => 'ADMINMAIL', 'name' => 'Email administrateur', 'family' => 'GLOBAL', 'type' => ConfigOption::OPTION_TEXT_REQUIRED),
            array('code' => 'COMPANYNAME', 'name' => 'Nom de la société', 'family' => 'GLOBAL', 'type' => ConfigOption::OPTION_TEXT_REQUIRED),
            array('code' => 'COMPANYWEBSITE', 'name' => 'Version de l\'application', 'family' => 'GLOBAL', 'type' => ConfigOption::OPTION_TEXT_REQUIRED),
            array('code' => 'VERSION', 'name' => 'Version de l\'application', 'family' => 'GLOBAL', 'type' => ConfigOption::OPTION_TEXT),
            array('code' => 'DESCRIPTION', 'name' => 'Version de l\'application', 'family' => 'GLOBAL', 'type' => ConfigOption::OPTION_TEXTAREA),
            
            // Thème
            array('code' => 'THEME_COLOR', 'name' => 'Couleur du thème', 'family' => 'THEME', 'type' => ConfigOption::OPTION_CHOICE_REQUIRED , ),
            array('code' => 'SIDEBAR_COLOR', 'name' => 'Couleur du menu (à gauche)', 'family' => 'THEME', 'type' => ConfigOption::OPTION_CHOICE_REQUIRED),
            array('code' => 'NAVBAR_COLOR', 'name' => 'Couleur de la barre de navigation (en haut)', 'family' => 'THEME', 'type' => ConfigOption::OPTION_CHOICE_REQUIRED),
            
        );
        
        foreach($options as $o){
            $option = new ConfigOption();
            $option->setName($o['name']);
            $option->setCode($o['code']);
            $option->setType($o['type']);
            $option->setOptionFamily($this->getReference('family-'.$o['family']));
            $manager->persist($option);
            
            $this->addReference('option-'.$o['code'], $option);
        }
        
        $manager->flush();
    }
    
    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 2;
    }
    
}



?>



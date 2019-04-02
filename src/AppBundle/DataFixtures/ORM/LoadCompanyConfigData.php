<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

    
class LoadCompanyConfigData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface  
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
    
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $companyConfig = new \AppBundle\Entity\CompanyConfig();
        $companyConfig->setActive(1);
       
        $manager->persist($companyConfig);
        $manager->flush();
        
        $this->container->get('app.config_handler')->setDefaultConfigOptions();
     }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 4; // the order in which fixtures will be loaded
    }
 
}

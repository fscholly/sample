<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\User;

class LoadUserData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface 
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
        
        //Admin
        $userAdmin = new User();
        $encoder = $this->container
            ->get('security.encoder_factory')
            ->getEncoder($userAdmin)
        ;
        $userAdmin->setUsername('admin');
        $userAdmin->setPassword($encoder->encodePassword('adminResonnance2017', $userAdmin->getSalt()));
        $userAdmin->setEnabled(1);
        $userAdmin->setEmail('francois@des-heros.com');
        $userAdmin->setFirstname('Admin');
        $userAdmin->setLastname('NSDH');
        $userAdmin->setDescription('Administrateur du site');
        $userAdmin->addGroup($this->getReference('group-admin'));
        
        $manager->persist($userAdmin);
        $this->addReference('user-admin', $userAdmin);

        $manager->flush();
        
    }
    

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 2; // the order in which fixtures will be loaded
    }
    
}

<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

use AppBundle\Entity\ImageType;

class LoadImageTypeData extends AbstractFixture implements OrderedFixtureInterface
{

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $types = array (
            array('code' => 'AVATAR_SMALL', 
                'maxWidth' => 30,'maxHeight' => 30, 
                'defaultFilepath' => '/images/default-avatar-small.jpg'),
            array('code' => 'AVATAR_MEDIUM', 
                'maxWidth' => 80,'maxHeight' => 80, 
                'defaultFilepath' => '/images/default-avatar-medium.jpg'),
            array('code' => 'AVATAR_LARGE', 
                'maxWidth' => 200,'maxHeight' => 200, 
                'defaultFilepath' => '/images/default-avatar-large.jpg'),
            array('code' => 'LOGO', 
                'maxWidth' => 500,'maxHeight' => 250, 
                'defaultFilepath' => '/images/default-logo.png'),
            array('code' => 'LOGO_FACTURE', 
                'maxWidth' => 500,'maxHeight' => 250, 
                'defaultFilepath' => '/images/default-logo-facture.png'),
            array('code' => 'PRODUCT_SMALL', 
                'maxWidth' => 50,'maxHeight' => 50, 
                'defaultFilepath' => '/images/default-product-small.png'),
            array('code' => 'PRODUCT_MEDIUM', 
                'maxWidth' => 150,'maxHeight' => 150, 
                'defaultFilepath' => '/images/default-product-medium.png'),
            array('code' => 'PRODUCT_LARGE', 
                'maxWidth' => 400,'maxHeight' => 400, 
                'defaultFilepath' => '/images/default-product-large.png'),
        );
        
        foreach($types as $t) {
            $imageType = new ImageType();
            $imageType->setCode($t['code']);
            $imageType->setMaxWidth($t['maxWidth']);
            $imageType->setMaxHeight($t['maxHeight']);
            $imageType->setDefaultFilepath($t['defaultFilepath']);
            
            $manager->persist($imageType);
        }
        
        $manager->flush();
    }
    
    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 1;
    }
    
}



?>



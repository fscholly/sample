<?php

namespace AppBundle\Controller;

use FOS\UserBundle\Controller\ProfileController as BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use FOS\UserBundle\Model\UserInterface;

/**
 * Controller managing the user profile
 *
 */
class ProfileController extends BaseController
{
    /**
     * Show the user
     */
    public function showAction()
    {
        $user = $this->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }
        
        $response = parent::showAction();
        return $response;
        
    }
    
    /**
     * Edit the user
     */
    public function editAction(Request $request)
    {
        //breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addItem("Ma page", $this->get("router")->generate("fos_user_profile_show"));
        $breadcrumbs->addItem('Editer le profil');
        
        $response = parent::editAction($request);
        return $response;
    }
}

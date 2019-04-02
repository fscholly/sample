<?php
namespace AppBundle\Controller;

use FOS\UserBundle\Controller\ChangePasswordController as BaseController;
use Symfony\Component\HttpFoundation\Request;

/**
 * Controller managing the password change
 *
 */
class ChangePasswordController extends BaseController
{
    /**
     * Change user password
     */
    public function changePasswordAction(Request $request)
    {
        //breadcrumb
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addItem("Ma page", $this->get("router")->generate("fos_user_profile_show"));
        $breadcrumbs->addItem("Modifier le mot de passe");
        
        $response = parent::changePasswordAction($request);
        return $response;
    }
}

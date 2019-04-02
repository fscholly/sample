<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use BankinBundle\Services\BankinHandler;

class BankinController extends Controller
{
    /**
     * @Route("/bankin/users", name="bankin_users")
     */
    public function usersAction(Request $request)
    {
        //Breadcrumbs
        $this->get('app.breadcrumbs_handler')->setBreadcrumbs(array(
            'label' => 'Liste des utilisateurs de l\'API Bankin',
            'route' => $this->get("router")->generate("bankin_users")
        ));
        $em = $this->getDoctrine()->getManager();
        
        
        $users = $this->get('bankin.user_handler')->getUsers();
        return $this->render('AppBundle:Bankin:users.html.twig', array(
            'users' => $users,
        ));
    }
    
    /**
     * @Route("/bankin/banks", name="bankin_banks")
     */
    public function banksAction(Request $request)
    {
        //Breadcrumbs
        $this->get('app.breadcrumbs_handler')->setBreadcrumbs(array(
            'label' => 'Liste des banques de l\'API Bankin',
            'route' => $this->get("router")->generate("bankin_banks")
        ));
        
        $banks = $this->get('bankin.bank_handler')->getBanks();
        return $this->render('AppBundle:Bankin:banks.html.twig', array(
            'banks' => $banks,
        ));
    }
    
    /**
     * @Route("/bankin/categories", name="bankin_categories")
     */
    public function categoriesAction(Request $request)
    {
        //Breadcrumbs
        $this->get('app.breadcrumbs_handler')->setBreadcrumbs(array(
            'label' => 'Liste des catégories de l\'API Bankin',
            'route' => $this->get("router")->generate("bankin_categories")
        ));
        
        $categoryHandler = $this->get('bankin.category_handler');
        $categories = $categoryHandler->getCategories();
        $names = [];
        $parents = [];
        foreach($categories as $key => $category) {
            $category['parent'] = empty($category['parent_id']) ? null : $categoryHandler->getCategoryName($category['parent_id']);
            $categories[$key] = $category;
            $parents[$key] = $category['parent'];
            $names[$key] = $category['name'];
        }
        array_multisort($parents, SORT_ASC, $names, SORT_ASC, $categories);
        
        return $this->render('AppBundle:Bankin:categories.html.twig', array(
            'categories' => $categories,
        ));
    }
}

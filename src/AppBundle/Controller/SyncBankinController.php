<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use BankinBundle\Services\BankinHandler;

class SyncBankinController extends Controller
{
    /**
     * @Route("/sync/bankin/categories", name="sync_bankin_categories")
     */
    public function categoriesAction(Request $request)
    {
        //Breadcrumbs
        $this->get('app.breadcrumbs_handler')->setBreadcrumbs(array(
            'label' => 'Synchronisation des categories Bankin',
            'route' => $this->get("router")->generate("sync_bankin_categories")
        ));
        $synced = $this->get('bankin.category_handler')->syncBankinCategories();
        $em = $this->getDoctrine()->getManager();
        $bankinCategories = $em->getRepository('BankinBundle:BankinCategory')->createQueryBuilder('bc')
            ->addOrderBy('bc.parent', 'ASC')
            ->addOrderBy('bc.name', 'ASC')
            ->getQuery()->getResult();
        
        return $this->render('AppBundle:SyncBankin:categories.html.twig', array(
            'bankinCategories' => $bankinCategories,
            'synced' => $synced,
        ));
    }
    
    /**
     * @Route("/sync/bankin/banks", name="sync_bankin_banks")
     */
    public function banksAction(Request $request)
    {
        //Breadcrumbs
        $this->get('app.breadcrumbs_handler')->setBreadcrumbs(array(
            'label' => 'Synchronisation des banques Bankin',
            'route' => $this->get("router")->generate("sync_bankin_banks")
        ));
        $synced = $this->get('bankin.bank_handler')->syncBankinBanks();
        $em = $this->getDoctrine()->getManager();
        $bankinBanks = $em->getRepository('BankinBundle:BankinBank')->createQueryBuilder('bb')
            ->addOrderBy('bb.countryCode', 'ASC')
            ->addOrderBy('bb.name', 'ASC')
            ->getQuery()->getResult();
        
        return $this->render('AppBundle:SyncBankin:banks.html.twig', array(
            'bankinBanks' => $bankinBanks,
            'synced' => $synced,
        ));
    }
}

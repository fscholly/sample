<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class BankinUserController extends Controller
{
    /**
     * @Route("/bankin/user/show", name="bankin_user_show")
     */
    public function showAction(Request $request)
    {
        //Breadcrumbs
        $this->get('app.breadcrumbs_handler')->setBreadcrumbs(array(
            'label' => 'Comptes bancaires',
            'route' => $this->get("router")->generate("bankin_user_show")
        ));
        
        $user = $this->getUser();
        
        $accountHandler = $this->get('bankin.account_handler');
        $bankHandler = $this->get('bankin.bank_handler');
        
        // Get list of accounts subscribed
        $accounts = $accountHandler->getBankinUserAccounts($user);
        foreach($accounts as $key => $account) {
            $account['bank'] = $bankHandler->getBankName($account['bank_id']);
            $accounts[$key] = $account;
        }
        
        // Get list of banks available
        $banks = $bankHandler->getBanks(['country_code' =>[\BankinBundle\Services\BankHandler::COUNTRY_CODE_FR]]);
       
        return $this->render('AppBundle:BankinUser:show.html.twig', array(
            'accounts' => $accounts,
            'banks' => $banks,
        ));
    }
    
    /**
     * @Route("/bankin/user/connect_item", name="bankin_user_connect_item")
     */
    public function connectItemAction(Request $request)
    {
        $bankId = $request->get('bankId', null);
        $bankinUser = $this->getUser()->getBankinUser();
        if(!$bankinUser) {
             throw new \Exception("Your are not synced with API Bankin.");
        }
        
        if($bankId) {
            $url = $this->get('bankin.user_handler')->getConnectBankUrl($bankinUser, $bankId);
            return $this->redirect($url, Response::HTTP_FOUND);
        }
        
        return $this->redirectToRoute('bankin_user_show');
    }
    
    
    
    /**
     * @Route("/public/bankin/connect_callback", name="bankin_user_connect_callback")
     */
    public function connectCallbackAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        // Récupération de l'id de l'item
        $itemId = $request->get('item_id', null);
        if(!$itemId) {
             throw new \Exception("Can't synchronized item. item_id is null.");
        }
        
        // Récupération de l'utilisateur bankin
        $userUuid = $request->get('user_uuid', 0);
        $bankinUser = $em->getRepository('BankinBundle:BankinUser')->findOneByUuid($userUuid);
        if(!$bankinUser) {
             throw new \Exception("Can't synchronized item. user_uuid not found.");
        }
        $accountHandler = $this->get('bankin.account_handler');
        
        $success = $accountHandler->connectItem($bankinUser, $itemId);
        if($success)
        {
            // récupération des accountIds liés à l'itemId
            "C'est good";
        }
        else {
            "c'est pas good";
        }
        die;
        return $this->redirectToRoute('bankin_user_show');
    }
    
    /**
     * @Route("/bankin/user/account/sync", name="bankin_user_account_sync")
     */
    public function syncAccountsAction(Request $request)
    {
        //Breadcrumbs
        $this->get('app.breadcrumbs_handler')->setBreadcrumbs(array(
            'label' => 'Synchronisation des comptes de l\'utilisateur',
            'route' => $this->get("router")->generate("bankin_user_account_sync")
        ));
        
        $user = $this->getUser();
        $synced = $this->get('bankin.account_handler')->syncUserBankinAccounts($user);
        $em = $this->getDoctrine()->getManager();
        $em->refresh($user);
        $bankinAccounts = $em->getRepository('BankinBundle:BankinAccount')->createQueryBuilder('ba')
            ->andWhere('ba.bankinUser = :bankinUser')
            ->addOrderBy('ba.name', 'ASC')
            ->setParameter('bankinUser', $user->getBankinUser())
            ->getQuery()->getResult();
        
        return $this->render('AppBundle:BankinUser:sync_accounts.html.twig', array(
            'bankinAccounts' => $bankinAccounts,
            'synced' => $synced,
        ));
    }
    
    
    /**
     * @Route("/bankin/user/account/{bankinAccountId}/transaction/sync", name="bankin_user_transaction_sync")
     * @ParamConverter("bankinAccount", class="BankinBundle:BankinAccount", options={"id" = "bankinAccountId"}) 
     */
    public function syncTransactionsAction(Request $request, \BankinBundle\Entity\BankinAccount $bankinAccount)
    {
        //Breadcrumbs
        $this->get('app.breadcrumbs_handler')->setBreadcrumbs(array(
            'label' => 'Synchronisation des opérations bancaires du compte de l\'utilisateur',
            'route' => $this->get("router")->generate("bankin_user_transaction_sync", $bankinAccount->routingArray())
        ));
        
        $user = $this->getUser();
        
        $synced = $this->get('bankin.transaction_handler')->syncUserBankinTransactions($user, $bankinAccount);
        $em = $this->getDoctrine()->getManager();
        $em->refresh($bankinAccount);
        $bankinTransactions = $bankinAccount->getBankinTransactions();
        
        return $this->render('AppBundle:BankinUser:sync_transactions.html.twig', array(
            'bankinAccount' => $bankinAccount,
            'bankinTransactions' => $bankinTransactions,
            'synced' => $synced,
        ));
    }
    
    /**
     * @Route("/bankin/user/account/{bankinAccountId}/transactions", name="bankin_user_transactions")
     * @ParamConverter("bankinAccount", class="BankinBundle:BankinAccount", options={"id" = "bankinAccountId"}) 
     */
    public function transactionsAction(Request $request, \BankinBundle\Entity\BankinAccount $bankinAccount)
    {
        //Breadcrumbs
        $this->get('app.breadcrumbs_handler')->setBreadcrumbs(array(
            'label' => 'Opérations bancaires du compte de l\'utilisateur',
            'route' => $this->get("router")->generate("bankin_user_transactions", $bankinAccount->routingArray())
        ));
        
        return $this->render('AppBundle:BankinUser:transactions.html.twig', array(
            'bankinAccount' => $bankinAccount,
        ));
    }
    
    
    
    /**
     * @Route("/bankin/user/account/{accountId}/test_transactions", name="bankin_user_account_test_transactions")
     */
    public function testTransactionAction(Request $request, $accountId)
    {
        //Breadcrumbs
        $this->get('app.breadcrumbs_handler')->setBreadcrumbs(array(
            'label' => 'Liste des transactions',
            'route' => $this->get("router")->generate("bankin_user_account_transactions", ['accountId' => $accountId])
        ));
        
        $user = $this->getUser();
        
        $transactionHandler = $this->get('bankin.transaction_handler');
        $categoryHandler = $this->get('bankin.category_handler');
        $accountHandler = $this->get('bankin.account_handler');
        
        // Récupérer la liste des transactions de l'utilisateur pour le compte
        $datetime_format = "Y-m-d\TH:i:s";
        $date_format = "Y-m-d";
        $date = \DateTime::createFromFormat($datetime_format, "2018-01-01T00:00:00");
        $options = array(
            'account_id' => $accountId,
            'until' => $date->format($date_format),
//            'since' => $date->format($date_format),
            'limit' => 500,
        );
        
        $transactions = $transactionHandler->getBankinUserTransactions($user, $options);
        
        // Get list of transactions subscribed
        foreach($transactions as $key => $transaction) {
            $transaction['category'] = $categoryHandler->getCategoryName($transaction['category_id']);
            $transactions[$key] = $transaction;
        }
       
        return $this->render('AppBundle:BankinUser:test_transactions.html.twig', array(
            'transactions' => $transactions,
            'date' => $date,
            'accountName' => $accountHandler->getBankinUserAccountName ($user, $accountId)
        ));
    }
}

<?php

namespace BankinBundle\Services;

use BankinBundle\Entity\BankinAccount;

use Symfony\Component\HttpFoundation\Response;
use GuzzleHttp\Exception\RequestException;


class AccountHandler extends ApiHandler
{
    const URI_ACCOUNTS = 'accounts';
    const URI_ITEMS = 'items';
    const ITEM_STATUS_OK = 0;
    
    public function __construct($em, $validator, $base_uri, $version, $client_id, $client_secret,
            UserHandler $userHandler
        )
    {
        parent::__construct($em, $validator, $base_uri, $version, $client_id, $client_secret);
        $this->userHandler = $userHandler;
    }
    

    /**
     * Récupérer la liste des comptes bancaires souscrits par le 'client' $user
     * 
     * @param type $user
     * @throws \Exception
     */
    public function getBankinUserAccountName ($user, $accountId)
    {
        $bankinUser = $user->getBankinUser();
        if(!$bankinUser) {
            $bankinUser = $this->userHandler->authenticateBankinUser($user);
        }
        
        return $this->getAccountName($bankinUser, $accountId);
    }
    
    /**
     * 
     * Récupérer le nom d'un compte à travers l'API
     * 
     * @param type $bankinUser
     * @param int $accountId
     * @return type
     * @throws \Exception
     */
    public function getAccountName ($bankinUser, $accountId)
    {
        $accessToken = $bankinUser->getAccessToken();
        
        $uri = self::URI_ACCOUNTS . '/'. $accountId;
        $response = $this->request('GET', $uri, [
            'headers' => $this->getAuthorizationHeaders($accessToken),
            'query' => $this->getQuery()
        ]);
         if($response->getStatusCode() == Response::HTTP_UNAUTHORIZED || $response->getStatusCode() == Response::HTTP_BAD_REQUEST) {
            $bankinUser = $this->userHandler->refreshToken($bankinUser);
            return $this->getAccountName($bankinUser, $accountId);
        }
        elseif($response->getStatusCode() != Response::HTTP_OK) {
            throw new \Exception("Can't get account '$accountId' for user '". $bankinUser->getUuid()."'." . $this->getResponseError($response));
        }
        $body = json_decode($response->getBody()->getContents(), true);
        return isset ($body['name']) ? $body['name'] : null;
    }
    
    /**
     * Récupérer la liste des comptes bancaires souscrits par le 'client' $user
     * 
     * @param type $user
     * @throws \Exception
     */
    public function getBankinUserAccounts ($user)
    {
        $bankinUser = $user->getBankinUser();
        if(!$bankinUser) {
            $bankinUser = $this->userHandler->authenticateBankinUser($user);
        }
        
        return $this->getAccounts($bankinUser);
    }
    
    /**
     * Récupérer la liste des comptes bancaires de l'utilisateur de l'API
     * 
     * @param type $bankinUser
     * @return type
     * @throws \Exception
     */
    public function getAccounts ($bankinUser)
    {
        $accessToken = $bankinUser->getAccessToken();
        
        $response = $this->request('GET', self::URI_ACCOUNTS, [
            'headers' => $this->getAuthorizationHeaders($accessToken),
            'query' => $this->getQuery()
        ]);
        
        if($response->getStatusCode() == Response::HTTP_UNAUTHORIZED || $response->getStatusCode() == Response::HTTP_BAD_REQUEST) {
            $bankinUser = $this->userHandler->refreshToken($bankinUser);
            return $this->getAccounts($bankinUser);
        }
        elseif($response->getStatusCode() != Response::HTTP_OK) {
            throw new \Exception("Can't get account for user '". $bankinUser->getUuid()."'." . $this->getResponseError($response));
        }
        $body = json_decode($response->getBody()->getContents(), true);
        
        // Récupérer les comptes valides pour l'utilisateur
        $accounts = [];
        foreach($body['resources'] as $account){
            if($account['status'] == self::ITEM_STATUS_OK) {
                $accounts [] = [
                    'id' => $account['id'],
                    'name' => $account['name'],
                    'balance' => $account['balance'],
                    'currency_code' => $account['currency_code'],
                    'type' => $account['type'],
                    'last_refresh_date' => isset($account['last_refresh_date']) ? $account['last_refresh_date'] : null,
                    'bank_id' => $account['bank']['id']
                ];
            }
        }
        return $accounts;
    }
    
    
    public function connectItem($bankinUser, $itemId)
    {
        $accessToken = $bankinUser->getAccessToken();
        $uri = self::URI_ITEMS . '/'. $itemId;
        $response = $this->request('GET', $uri, [
            'headers' => $this->getAuthorizationHeaders($accessToken),
            'query' => $this->getQuery()
        ]);
        
        if($response->getStatusCode() == Response::HTTP_UNAUTHORIZED || $response->getStatusCode() == Response::HTTP_BAD_REQUEST) {
            $bankinUser = $this->userHandler->refreshToken($bankinUser);
            return $this->connectItem($bankinUser, $itemId);
        }
        elseif($response->getStatusCode() != Response::HTTP_OK) {
            throw new \Exception("Can't connect item '$itemId' for user '". $bankinUser->getUuid()."'." . $this->getResponseError($response));
        }
        $body = json_decode($response->getBody()->getContents(), true);
        
        // Refresh item
        $this->refreshItemStatus ($bankinUser, $itemId);
        
        // Check item status
        $status = $this->getItemStatus($bankinUser, $itemId);
        switch($status)
        {
            case self::ITEM_REFRESH_STATUS_AUTHENTICATING:
            case self::ITEM_REFRESH_STATUS_RETRIEVING_DATA:
                sleep(1);
                return $this->connectItem($bankinUser, $itemId);
            case self::ITEM_REFRESH_STATUS_FINISHED:
                return true;
            case self::ITEM_REFRESH_STATUS_INFO_REQUIRED:
            case self::ITEM_REFRESH_STATUS_INVALID_CREDS:
            case self::ITEM_REFRESH_STATUS_FINISHED_ERROR:
            default:
                // à traiter plus tard
                break;
        }
        return false;
    }
    
    public function refreshItemStatus ($bankinUser, $itemId)
    {
        $accessToken = $bankinUser->getAccessToken();
        $uri = self::URI_ITEMS . '/'. $itemId. '/refresh/status';
        $response = $this->request('POST', $uri, [
            'headers' => $this->getAuthorizationHeaders($accessToken),
            'query' => $this->getQuery()
        ]);
        
        if($response->getStatusCode() == Response::HTTP_UNAUTHORIZED || $response->getStatusCode() == Response::HTTP_BAD_REQUEST) {
            $bankinUser = $this->userHandler->refreshToken($bankinUser);
            return $this->refreshItemStatus($bankinUser, $itemId);
        }
        elseif($response->getStatusCode() != Response::HTTP_ACCEPTED) {
            throw new \Exception("Can't refresh status for item '$itemId' for user '". $bankinUser->getUuid()."'." . $this->getResponseError($response));
        }
    }
    
    public function getItemStatus ($bankinUser, $itemId)
    {
        $accessToken = $bankinUser->getAccessToken();
        $uri = self::URI_ITEMS . '/'. $itemId. '/refresh/status';
        $response = $this->request('GET', $uri, [
            'headers' => $this->getAuthorizationHeaders($accessToken),
            'query' => $this->getQuery()
        ]);
        
        if($response->getStatusCode() == Response::HTTP_UNAUTHORIZED || $response->getStatusCode() == Response::HTTP_BAD_REQUEST) {
            $bankinUser = $this->userHandler->refreshToken($bankinUser);
            return $this->getItemStatus($bankinUser, $itemId);
        }
        elseif($response->getStatusCode() != Response::HTTP_OK) {
            throw new \Exception("Can't get item '$itemId' status for user '". $bankinUser->getUuid()."'." . $this->getResponseError($response));
        }
    }
    
    /**
     * Récupérer la liste des comptes bancaires souscrits par le 'client' $user
     * 
     * @param type $user
     * @throws \Exception
     */
    public function syncUserBankinAccounts ($user, $accounts = [])
    {
        $bankinUser = $user->getBankinUser();
        if(!$bankinUser) {
            $bankinUser = $this->userHandler->authenticateBankinUser($user);
        }
        
        return $this->syncBankinAccounts($bankinUser, $accounts);
    }
    
    public function syncBankinAccounts ($bankinUser, $accounts = [])
    {
        if(empty($accounts)) {
            $accounts = $this->getAccounts ($bankinUser);
        }
        
        $synced = 0;
        $bankinAccountRepo = $this->em->getRepository('BankinBundle:BankinAccount');
        // Enregistrer les informations renvoyés par l'API
        foreach($accounts as $account)
        {
            $bankinAccount = $bankinAccountRepo->findOneBy(['accountId' => $account['id'], 'bankinUser' => $bankinUser]);
            if(!$bankinAccount) {
                $this->addBankinAccount($bankinUser, $account);
                $synced++;
            }
        }
        
        return $synced;
    }
    
    public function addBankinAccount ($bankinUser, $account)
    {
        $bankinAccount = new BankinAccount();
        $bankinAccount->setBankinUser($bankinUser);
        $bankinAccount->setAccountId($account['id']);
        $bankinAccount->setName($account['name']);
        $bankinAccount->setBalance($account['balance']);
        $bankinAccount->setCurrencyCode($account['currency_code']);
        $bankinAccount->setType($account['type']);
        
        $lastRefreshDateStr = $account['last_refresh_date'];
        $lastRefreshDate = $lastRefreshDateStr ? \DateTime::createFromFormat("Y-m-d\TH:i:s",$lastRefreshDateStr) : null;
        $bankinAccount->setLastRefreshDate($lastRefreshDate);
        
        $bankinBank = $this->em->getRepository('BankinBundle:BankinBank')->findOneByBankId($account['bank_id']);
        if(!$bankinBank){
             throw new \Exception("Can't add account because bank ". $account['bank_id'] . " not found");
        }
        $bankinAccount->setBankinBank($bankinBank);
        $bankinAccount->setSync(true);
        $bankinAccount->setLastSyncDate(new \DateTime("now"));
            
        $this->em->persist($bankinAccount);
        $this->em->flush();
    }
    
}

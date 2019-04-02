<?php

namespace BankinBundle\Services;

use BankinBundle\Entity\BankinTransaction;

use Symfony\Component\HttpFoundation\Response;
use GuzzleHttp\Exception\RequestException;


class TransactionHandler extends ApiHandler
{
    const URI_TRANSACTIONS = 'transactions';
    
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
     * @param array $options
     * @throws \Exception
     */
    public function getBankinUserTransactions ($user, $options)
    {
        $bankinUser = $user->getBankinUser();
        if(!$bankinUser) {
            $bankinUser = $this->userHandler->authenticateBankinUser($user);
        }
        
        return $this->getTransactions($bankinUser, $options);
    }
    
    
    /**
     * Récupérer la liste des transactions de l'utilisateur de l'API
     * 
     * @param type $bankinUser
     * @param array $options
     * @return type
     * @throws \Exception
     */
    public function getTransactions ($bankinUser, $options)
    {
        $accessToken = $bankinUser->getAccessToken();
        
        $response = $this->request('GET', self::URI_TRANSACTIONS, [
            'headers' => $this->getAuthorizationHeaders($accessToken),
            'query' => $this->getQuery($options)
        ]);
        
        if($response->getStatusCode() == Response::HTTP_UNAUTHORIZED || $response->getStatusCode() == Response::HTTP_BAD_REQUEST) {
            $bankinUser = $this->userHandler->refreshToken($bankinUser);
            return $this->getTransactions($bankinUser, $options);
        }
        elseif($response->getStatusCode() != Response::HTTP_OK) {
            throw new \Exception("Can't get transaction for user '". $bankinUser->getUuid()."'." . $this->getResponseError($response));
        }
        $body = json_decode($response->getBody()->getContents(), true);
        
        // Récupérer les comptes valides pour l'utilisateur
        $transactions = [];
        foreach($body['resources'] as $transaction){
            if($transaction['account']['id'] == $options['account_id']) {
                $transactions [] = [
                    'id' => $transaction['id'],
                    'description' => $transaction['description'],
                    'raw_description' => $transaction['raw_description'],
                    'date' => $transaction['date'],
                    'amount' => $transaction['amount'],
                    'updated_at' => $transaction['updated_at'],
                    'is_deleted' => $transaction['is_deleted'],
                    'category_id' => $transaction['category']['id'],
                    'account_id' => $transaction['account']['id'],
                ];
            }
        }
        return $transactions;
    }
    
    /**
     * Récupérer la liste des transactions pour le compte bancaire de  $user
     * 
     * @param type $user
     * @throws \Exception
     */
    public function syncUserBankinTransactions ($user, $bankinAccount, $transactions = [])
    {
        $bankinUser = $user->getBankinUser();
        if(!$bankinUser) {
            $bankinUser = $this->userHandler->authenticateBankinUser($user);
        }
        
        return $this->syncBankinTransactions($bankinUser, $bankinAccount, $transactions);
    }
    
    public function syncBankinTransactions ($bankinUser, $bankinAccount, $transactions = [])
    {
        if(empty($transactions)) {
            $date = $bankinAccount->getLastSyncDate() ? $bankinAccount->getLastSyncDate() : new \DateTime("now");
            
            $transactions = $this->getTransactions ($bankinUser, [
                'account_id' => $bankinAccount->getAccountId(),
                'until' => $date->format("Y-m-d"),
                'limit' => 500
            ]);
        }
        
        $synced = 0;
        $bankinTransactionRepo = $this->em->getRepository('BankinBundle:BankinTransaction');
        // Enregistrer les informations renvoyés par l'API
        foreach($transactions as $transaction)
        {
            $bankinTransaction = $bankinTransactionRepo->findOneBy(['transactionId' => $transaction['id'], 'bankinAccount' => $bankinAccount]);
            if(!$bankinTransaction) {
                $this->addBankinTransaction($bankinAccount, $transaction);
                $synced++;
            }
        }
        
        return $synced;
    }
    
    public function addBankinTransaction ($bankinAccount, $transaction)
    {
        if($transaction['is_deleted']) {
            return $this->removeBankinTransaction($bankinAccount, $transaction['id']);
        }
        
        $bankinTransaction = new BankinTransaction();
        $bankinTransaction->setBankinAccount($bankinAccount);
        $bankinTransaction->setTransactionId($transaction['id']);
        $bankinTransaction->setDescription($transaction['description']);
        $bankinTransaction->setRawDescription($transaction['raw_description']);
        $bankinTransaction->setAmount($transaction['amount']);
        
        $bankinCategory = $this->em->getRepository('BankinBundle:BankinCategory')->findOneByCategoryId($transaction['category_id']);
        if(!$bankinCategory){
             throw new \Exception("Can't add transaction  because category ". $transaction['category_id'] . " not found");
        }
        $bankinTransaction->setBankinCategory($bankinCategory);
        
        $date = \DateTime::createFromFormat("Y-m-d", $transaction['date']);
        if($date === false) {
             throw new \Exception("Can't convert date '". $transaction['date']. "' for transaction_id " . $transaction['id']);
        }
        $bankinTransaction->setDate($date);
        
        $updateDate = \DateTime::createFromFormat("Y-m-d\TH:i:s.u\Z", $transaction['updated_at']);
        if($updateDate === false) {
             throw new \Exception("Can't convert update_at '". $transaction['updated_at']. "' for transaction_id " . $transaction['id']);
        }
        $bankinTransaction->setUpdateDate($updateDate);
            
        $this->em->persist($bankinTransaction);
        $this->em->flush();
    }
    
    
    public function removeBankinTransaction($bankinAccount, $transactionId)
    {
        
    }
}

<?php

namespace BankinBundle\Services;

use BankinBundle\Services\ApiHandler;
use BankinBundle\Entity\BankinBank;

use Symfony\Component\HttpFoundation\Response;
use GuzzleHttp\Exception\RequestException;


class BankHandler extends ApiHandler
{
    const URI_BANKS = 'banks';
    const COUNTRY_CODE_FR = 'FR';
    const COUNTRY_CODE_DE = 'DE';
    const COUNTRY_CODE_ES = 'ES';
    const COUNTRY_CODE_GB = 'GB';
    
    public function __construct($em, $validator, $base_uri, $version, $client_id, $client_secret)
    {
        parent::__construct($em, $validator, $base_uri, $version, $client_id, $client_secret);
    }
    
    /**
     * 
     * Récupérer le nom d'une banque à travers l'API
     * 
     * @param type $bankId
     * @return type
     * @throws \Exception
     */
    public function getBankName ($bankId)
    {
        $uri = self::URI_BANKS . '/'. $bankId;
        $response = $this->request('GET', $uri, [
            'query' => $this->getQuery()
        ]);
        if($response->getStatusCode() != Response::HTTP_OK) {
            throw new \Exception("Can't retrieve bank name for bankId '$bankId'." . $this->getResponseError($response));
        }
        $body = json_decode($response->getBody()->getContents(), true);
        return isset ($body['name']) ? $body['name'] : null;
    }
    
    /**
     * Récupérer la liste des banques disponibles à partir de l'API
     * 
     * @param type $filters
     * @return type
     * @throws \Exception
     */
    public function getBanks($filters = [])
    {
        $response = $this->request('GET', self::URI_BANKS , [
            'query' => $this->getQuery([
                'limit' => 1000,
            ])
        ]);
        
        if($response->getStatusCode() != Response::HTTP_OK) {
            throw new \Exception("Can't get banks list." . $this->getResponseError($response));
        }
        $body = json_decode($response->getBody()->getContents(), true);
        
        $banks = [];
        $filterCountry = isset($filters['country_code']) && !empty($filters['country_code']) ? true : false;
        foreach($body['resources'] as $bank){
            if(($filterCountry && in_array($bank['country_code'], $filters['country_code'])) || !$filterCountry)
            {
                $banks [] = [
                    'id' => $bank['id'],
                    'name' => $bank['name'],
                    'country_code' => $bank['country_code']
                ];
            }
        }
        
        return $banks;
    }
    
    
    public function syncBankinBanks ($banks = [])
    {
        if(empty($banks)) {
            $banks = $this->getBanks();
        }
        
        $synced = 0;
        $bankinBankRepo = $this->em->getRepository('BankinBundle:BankinBank');
        // Enregistrer les informations renvoyés par l'API
        foreach($banks as $bank)
        {
            $bankinBank = $bankinBankRepo->findOneByBankId($bank['id']);
            if(!$bankinBank) {
                $this->addBankinBank($bank);
                $synced++;
            }
        }
        
        return $synced;
    }
    
    public function addBankinBank ($bank)
    {
        $bankinBank = new BankinBank();
        $bankinBank->setBankId($bank['id']);
        $bankinBank->setName($bank['name']);
        $bankinBank->setCountryCode($bank['country_code']);
        
        $this->em->persist($bankinBank);
        $this->em->flush();
    }
}

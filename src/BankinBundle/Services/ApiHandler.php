<?php

namespace BankinBundle\Services;

use Psr\Log\LoggerInterface;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Validator\Validator\RecursiveValidator;

use GuzzleHttp\Exception\RequestException;
use BankinBundle\Entity\BankinUser;

abstract class ApiHandler implements ItemInterface
{
    protected $base_uri;
    protected $version;
    protected $client_id;
    protected $client_secret;
    protected $client;
    
    protected $validator;
    protected $logger;
    

    public function __construct (
            EntityManager $em,
            RecursiveValidator $validator,
            $base_uri, 
            $version, 
            $client_id, $client_secret
    )
    {
        $this->em = $em;
        $this->base_uri = $base_uri;
        $this->version = $version;
        $this->client_id = $client_id ;
        $this->client_secret = $client_secret;
        
        $this->validator = $validator;
        
        $this->client = new \GuzzleHttp\Client([
            'base_uri' => $this->base_uri,
            'headers' => [
                'Bankin-Version' => $this->version
            ],
        ]);
        
    }
    
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
    
    public function getAuthorizationHeaders($token) 
    {
        return ['Authorization' => 'Bearer '.$token];
    }

    public function getAcceptedLanguage($locale = 'fr')
    {
        return ['Accept-Language' => $locale];
    }
    
    public function getQuery($params = []) 
    {
        return array_merge($params, [
            'client_id' => $this->client_id, 
            'client_secret' => $this->client_secret
        ]);
    }
    
    public function request($method, $uri, $params)
    {
        try {
            return $this->client->request($method, $uri, $params);
        } 
        catch (RequestException $e) {
            if ($e->hasResponse()) {
                return $e->getResponse();
            }
        }
    }
   
    protected function getResponseError($response)
    {
        return "[Code]". $response->getStatusCode() . "[Reason]".$response->getReasonPhrase();
    }
    
        
    
    
   
    
    
}

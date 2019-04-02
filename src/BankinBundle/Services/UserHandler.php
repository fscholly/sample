<?php

namespace BankinBundle\Services;

use BankinBundle\Services\ApiHandler;
use BankinBundle\Entity\BankinUser;

use Symfony\Component\HttpFoundation\Response;


class UserHandler extends ApiHandler
{
    
    const URI_USERS = 'users';
    const URI_AUTH = 'authenticate';
    const URI_CONNECT = 'items/connect';
    
    public function __construct($em, $validator, $base_uri, $version, $client_id, $client_secret)
    {
        parent::__construct($em, $validator, $base_uri, $version, $client_id, $client_secret);
    }
    
    /**
     * Générer l'url qui permet d'accéder au formulaire d'autorisation de synchronisation 
     * d'un compte bancaire
     * avec l'utilisateur de l'API
     * 
     * @param BankinUser $bankUser
     * @param type $bankId
     * @return type
     */
    public function getConnectBankUrl (BankinUser $bankinUser, $bankId)
    {
        $accessToken = $bankinUser->getAccessToken();
        return $this->base_uri . self::URI_CONNECT . "?client_id=".$this->client_id . "&bank_id=".$bankId . "&access_token=" . $accessToken;
    }
    
    /**
     * Créer un utilisateur de l'API Bankin et l'associer à un 'client' $user
     * 
     * @param type $user
     * @return BankinUser
     * @throws \Exception
     */
    public function createBankinUser($user)
    {
        if($user->getBankinUser()) return $user->getBankinUser();
        
        $email = $user->getEmail();
        if(!$email) {
            throw new \Exception("Can't create bankin account because 'email' is null");
        }
        
        $userParams = $this->addUser($email);
        $uuid = $userParams['uuid'];
        $email = $userParams['email'];
        $password = $userParams['password'];
        
        $bankinUser = new BankinUser();
        $bankinUser->setUuid($uuid);
        $bankinUser->setEmail($email);
        $bankinUser->setPassword($password);
        if(!$this->validator->validate($bankinUser)) {
            throw new \Exception("Can't create bankin user because uuid '$uuid' & email '$email' already exist. password is '$password'");
        }
        $user->setBankinUser($bankinUser);
        $this->em->persist($bankinUser);
        $this->em->persist($user);
        $this->em->flush();
        
        return $bankinUser;
    }
    
    /**
     * Ajouter un utilisateur de l'API Bankin à partir d'une adresse email
     * 
     * @param type $email
     * @param type $password
     * @return type
     * @throws \Exception
     */
    public function addUser($email, $password = null)
    {
        // Générer un mot de passe si aucun fourni
        $password = $password ? $password : uniqid();
        
        $response = $this->request('POST', self::URI_USERS, [
            'query' => $this->getQuery([
                'email' => $email,
                'password' => $password,
            ])
        ]);
        
        if($response->getStatusCode() != Response::HTTP_CREATED) {
            throw new \Exception("Can't create bankin account for '$email'". $this->getResponseError($response));
        }
        
        $body = json_decode($response->getBody()->getContents(), true);
        $uuid = isset ($body['uuid']) ? $body['uuid'] : null;
        
        if(empty($uuid)) {
            throw new \Exception("User '$email' created, without uuid.");
        }
        
        return [
            'uuid' => $uuid,
            'email' => $email,
            'password' => $password,
        ];
    }
    
    /**
     * Supprimer un utilisateur de l'API Bankin et le dissocier du 'client' $user
     * 
     * @param type $user
     * @return boolean
     * @throws \Exception
     */
    public function removeBankinUser($user)
    {
        $bankinUser = $user->getBankinUser();
        if(!$bankinUser) return false;
        
        $uuid = $bankinUser->getUuid();
        if(!$uuid) {
            throw new \Exception("Can't remove bankin account because 'uuid' is null");
        }
        
        $uuid = $this->deleteBankinUser($email);
        $bankinUser = $this->em->getRepository('BankinBundle:BankinUser')->findOneByUuid($uuid);
        if(!$bankinUser) return false;
        
        $this->em->remove($bankinUser);
        $this->em->flush();
        
        return true;
    }
    
    /**
     * Supprimer un utilisateur de l'API
     * 
     * @param type $uuid
     * @param type $password
     * @return type
     * @throws \Exception
     */
    public function deleteUser($uuid, $password)
    {
        $uri = self::URI_USERS . '/'. $uuid;
        $response = $this->request('DELETE', $uri, [
            'query' => $this->getQuery([
                'password' => $password,
            ])
        ]);
        
        if($response->getStatusCode() != Response::HTTP_NO_CONTENT) {
            throw new \Exception("Can't delete bankin user '$uuid'." . $this->getResponseError($response));
        }
        
        return $uuid;
    }
    
    /**
     * Renvoyer la liste des utilisateurs de l'API
     * 
     * @return boolean|array
     * @throws \Exception
     */
    public function getUsers()
    {
        $response = $this->request('GET', self::URI_USERS , [
            'query' => $this->getQuery([
                'limit' => 1000,
            ])
        ]);
        
        if($response->getStatusCode() != Response::HTTP_OK) {
            return false;
        }
        $body = json_decode($response->getBody()->getContents(), true);
        
        $users = [];
        foreach($body['resources'] as $user){
                $users [] = [
                    'uuid' => $user['uuid'],
                    'email' => $user['email'],
                ];
        }
        
        return $users;
    }
    
    
    /**
     * Authentifier le client $user à l'API
     * 
     * @param type $user
     * @return type
     * @throws \Exception
     */
    public function authenticateBankinUser($user)
    {
        $bankinUser = $user->getBankinUser();
        if(!$bankinUser) {
            // Création du compte User dans Bankin
            $bankinUser = $this->createBankinUser($user);
        }
        
        $accessToken = $this->authenticate($bankinUser);
        return $this->updateAccessToken($bankinUser, $accessToken);
    }
    
    /**
     * Authentifier l'utilisateur de l'API
     * 
     * @param type $bankinUser
     * @return string
     * @throws \Exception
     */
    public function authenticate(BankinUser $bankinUser)
    {
        $email = $bankinUser->getEmail();
        $password = $bankinUser->getPassword();
        
        $response = $this->request('POST', self::URI_AUTH, [
            'query' => $this->getQuery([
                'email' => $email,
                'password' => $password,
            ])
        ]);
        if($response->getStatusCode() != Response::HTTP_OK) {
            throw new \Exception("Unable to authenticate bankinUser for uuid : '". $bankinUser->getUuid(). "'");
        }
        
        $body = json_decode($response->getBody()->getContents(), true);
        $accessToken = isset ($body['access_token']) ? $body['access_token'] : null;
        if(empty($accessToken)) {
            throw new \Exception("No access_token provided by bankin.");
        }
        
        return $accessToken;
    }
    
    
    public function updateAccessToken($bankinUser, $accessToken)
    {
         // Mettre à jour le token d'accès
        $bankinUser->setAccessToken($accessToken);
        $this->em->persist($bankinUser);
        $this->em->flush();
        
        return $bankinUser;
    }
    
    
    public function refreshToken (BankinUser $bankinUser)
    {
        $accessToken= $this->authenticate($bankinUser);
        $bankinUser = $this->updateAccessToken($bankinUser, $accessToken);
        
        return $bankinUser;
    }
}

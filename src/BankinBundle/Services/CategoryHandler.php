<?php

namespace BankinBundle\Services;

use BankinBundle\Services\ApiHandler;
use BankinBundle\Entity\BankinCategory;

use Symfony\Component\HttpFoundation\Response;
use GuzzleHttp\Exception\RequestException;


class CategoryHandler extends ApiHandler
{
    const URI_CATEGORIES = 'categories';
    
    public function __construct($em, $validator, $base_uri, $version, $client_id, $client_secret)
    {
        parent::__construct($em, $validator, $base_uri, $version, $client_id, $client_secret);
    }
    
    /**
     * 
     * Récupérer le nom d'une banque à travers l'API
     * 
     * @param type $categoryId
     * @return type
     * @throws \Exception
     */
    public function getCategoryName ($categoryId)
    {
        $uri = self::URI_CATEGORIES . '/'. $categoryId;
        $response = $this->request('GET', $uri, [
            'headers' => $this->getAcceptedLanguage(),
            'query' => $this->getQuery()
        ]);
        if($response->getStatusCode() != Response::HTTP_OK) {
            throw new \Exception("Can't retrieve category name for categoryId '$categoryId'." . $this->getResponseError($response));
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
    public function getCategories($filters = [])
    {
        $response = $this->request('GET', self::URI_CATEGORIES , [
            'headers' => $this->getAcceptedLanguage(),
            'query' => $this->getQuery([
                'limit' => 1000,
            ])
        ]);
        
        if($response->getStatusCode() != Response::HTTP_OK) {
            throw new \Exception("Can't get categories list." . $this->getResponseError($response));
        }
        $body = json_decode($response->getBody()->getContents(), true);
        
        $categories = [];
        foreach($body['resources'] as $category){
            $categories [] = [
                'id' => $category['id'],
                'name' => $category['name'],
                'parent_id' => isset($category['parent']['id']) ? $category['parent']['id'] : null,
            ];
        }
        
        return $categories;
    }
    
    public function syncBankinCategories ($categories = [])
    {
        if(empty($categories)) {
            $categories = $this->getCategories();
        }
        
        $synced = 0;
        $bankinCategoryRepo = $this->em->getRepository('BankinBundle:BankinCategory');
        // Enregistrer les informations renvoyés par l'API
        foreach($categories as $category)
        {
            $bankinCategory = $bankinCategoryRepo->findOneByCategoryId($category['id']);
            if(!$bankinCategory) {
                $this->addBankinCategory($category);
                $synced++;
            }
        }
        // Lier avec la catégorie parente
        $bankinCategories = $bankinCategoryRepo->findAll();
        foreach($bankinCategories as $bankinCategory)
        {
            $this->updateParentBankinCategory($bankinCategory);
        }
        
        return $synced;
    }
    
    public function addBankinCategory ($category)
    {
        $bankinCategory = new BankinCategory();
        $bankinCategory->setCategoryId($category['id']);
        $bankinCategory->setName($category['name']);
        $bankinCategory->setParentId($category['parent_id']);
        
        $this->em->persist($bankinCategory);
        $this->em->flush();
    }
    
    public function updateParentBankinCategory ($bankinCategory)
    {
        $parentId = $bankinCategory->getParentId();
        if($parentId) {
            $parent = $this->em->getRepository('BankinBundle:BankinCategory')->findOneByCategoryId($parentId);
            if(!$parent) {
               throw new \Exception("Can't updateParentBankinCategory for bankinCategoryId ". $bankinCategory->getCategoryId());
            }
            $bankinCategory->setParent($parent);
            $this->em->persist($bankinCategory);
            $this->em->flush();
        }
    }
}

<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * Document.
 *
 * @ORM\Table(name="nsdh_document")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\DocumentRepository")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({"document" = "Document", "image" = "Image"})
 * @ORM\HasLifecycleCallbacks
 */
class Document
{
    const MAX_FILESIZE = 10485760; // 10 Mo
    
    
    /**
     * Constructor.
     */
    public function __construct()
    {
    }
    
    public function routingArray()
    {
        return array(
            'documentId' => $this->id,
        );
    }
    
    public function __toString()
    {
        return "document #".$this->id;
    }
    
    
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @var text $discr
     */
    protected $discr;

    /*
     * Hook timestampable behavior
     * updates createdAt, updatedAt fields
     */
    use TimestampableEntity;

    
    /**
     * @Assert\File(maxSize="10485760")
     */
    protected $file;
    
    
    /**
     * @ORM\Column(name="filepath",type="string", length=255, nullable=true)
     */
    protected $filepath;
    
    
    /**
     * @Assert\Callback
     */
    public function checkFileSize(ExecutionContextInterface $context)
    {
        // Vérifier que la taille du fichier
        if ($this->file && file_exists($this->file) && filesize($this->file) > self::MAX_FILESIZE) {
            $context->buildViolation('Fichier trop volumineux. Taille de fichier limité à 10Mo.')
                    ->atPath('file')
                    ->addViolation();
        }
    }
    
    
    /**
     * Vérifier que le dossier d'upload existe
     *
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function checkUploadRootDir()
    {
        $uploadRootDir = $this->getUploadRootDir();
        if (!file_exists($uploadRootDir)) {
            mkdir($uploadRootDir, 0775, true);
        }
    }
    
    /**
     * Préparer l'upload du nouveau fichier
     *
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function prepareUpload()
    {
        if (null !== $this->file) {
            // Supprimer l'ancien fichier
            $this->removeUpload();
            
            // générer un nouveau nom unique pour le futur fichier
            $ext = pathinfo($this->file->getClientOriginalName(), PATHINFO_EXTENSION);
            $unique_id = uniqid($this->id);
            $this->filepath = 'document-'.$unique_id.'.'.$ext;
        }
    }

    
    /**
     * Supprimer l'ancien document
     * Récupérer le document uploadé
     *
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        if (null === $this->file) {
            return;
        }

        // s'il y a une erreur lors du déplacement du fichier, une exception
        // va automatiquement être lancée par la méthode move(). Cela va empêcher
        // proprement l'entité d'être persistée dans la base de données si erreur il y a

        $this->file->move($this->getUploadRootDir(), $this->filepath);
        
        unset($this->file);
    }

    /**
     * Créer un fichier à partir d'une fichier source
     * 
     * @param type $source     Chemin absolu vers le fichier source à copier
     * @param type $filepath   Nom du fichier destination
     */
    public function createFrom($source, $filepath) 
    {
        // Supprimer l'ancien fichier
        $this->removeUpload();
        
        // Copier le fichier
        copy($source, $this->getUploadRootDir() .'/'. $filepath);
        $this->setFilepath($filepath);
    }
    
    /**
     * Delete file after delete entity.
     *
     * @ORM\PreRemove()
     */
    public function removeUpload()
    {
        if ($this->filepath && ($file = $this->getAbsolutePath()) && (file_exists($this->getAbsolutePath()))) {
            unlink($file);
        }
    }
    
    /**
     * Get upload tmp root dir.
     *
     * @return string
     */
    public function getTmpRootDir()
    {
        return __DIR__.'/../../../var/tmp';
    }
    
    /**
     * Get upload root dir.
     *
     * @return string
     */
    public function getUploadRootDir()
    {
        // le chemin absolu du répertoire où les documents uploadés doivent être sauvegardés
        return __DIR__.'/../../../web'.$this->getUploadDir();
    }

    /**
     * Get upload dir (for web accesss).
     *
     * @return string
     */
    public function getUploadDir()
    {
        // on se débarrasse de « __DIR__ » afin de ne pas avoir de problème lorsqu'on affiche
        // le document/image dans la vue.
        
        return '/uploads/documents';
    }
    
    
    /**
     * Get absolute path for document
     *
     * @return string
     */
    public function getAbsolutePath()
    {
        if (null === $this->filepath) {
            return null;
        }

        return $this->getUploadRootDir().'/'.$this->filepath;
    }
    
    /**
     * Get web path for document
     *
     * @return string
     */
    public function getWebPath()
    {
        if (null === $this->filepath) {
            return null;
        }

        return $this->getUploadDir().'/'.$this->filepath;
    }
    
    
    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * Set file.
     *
     * @param string $file
     *
     * @return Document
     */
    public function setFile($file)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * Get file.
     *
     * @return string
     */
    public function getFile()
    {
        return $this->file;
    }


    /**
     * Set filepath
     *
     * @param string $filepath
     *
     * @return Document
     */
    public function setFilepath($filepath)
    {
        $this->filepath = $filepath;

        return $this;
    }

    /**
     * Get filepath
     *
     * @return string
     */
    public function getFilepath()
    {
        return $this->filepath;
    }
}

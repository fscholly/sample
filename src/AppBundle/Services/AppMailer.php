<?php

namespace AppBundle\Services;

use Symfony\Bridge\Monolog\Logger;

class AppMailer
{
    protected $appHelper;
    protected $mailer;
    protected $logger;
    
    private $customer_mail;
    private $prod_mail;
    private $admin_mail;

    public function __construct(\AppBundle\Services\AppHelper $appHelper, $mailer, Logger $logger, $customer, $prod, $admin)
    {
        $this->appHelper = $appHelper;
        $this->mailer = $mailer;
        $this->logger = $logger;
        $this->customer_mail = $customer;
        $this->prod_mail = $prod;
        $this->admin_mail = $admin;
    }
    
    public function send($subject, $body, $recipient, $from = null)
    {
        if(empty($from)) {
            $from = $this->admin_mail;
        }
        $message = \Swift_Message::newInstance()
            ->setSubject('Nous sommes des héros - '. $subject)
            ->setFrom($from)
            ->setContentType('text/html')
            ->setBody($body, 'text/html')
            ->setTo($recipient)
            ->attach(Swift_Attachment::fromPath('/path/to/a/file.zip'))    
        ;
        $sent = $this->mailer->send($message);
        
        // Log
        $status = $sent ? "[SENT]" : "[NOT SENT]";
        $this->logger->debug($status . " [subject]" . $subject . "[recipient]" . $recipient);
        
        return $sent;
    }
    
    public function sendToAdmin($subject, $body, $recipient)
    {
        return $this->send($subject, $body, $recipient, $this->admin_mail);
    }
    
    public function sendToCustomerService($subject, $body, $recipient)
    {
        return $this->send($subject, $body, $recipient, $this->customer_mail);
    }
    
    public function sendToProdService($subject, $body, $recipient)
    {
        return $this->send($subject, $body, $recipient, $this->prod_mail);
    }
    
    
}

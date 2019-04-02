<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;


class TestCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('app:test')
            ->setDescription('Resonnance - Test fonctionnel)')
            ->addArgument('name', InputArgument::REQUIRED, 'Que voulez-vous tester ?')
            ->addArgument('args', InputArgument::IS_ARRAY, 'Options')
            ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $cmd = $input->getArgument('name');

        $container = $this->getContainer();
        $em = $container->get('doctrine')->getManager();

        switch($cmd){
            case 'send_mail':
                list($mailTo) = $input->getArgument('args');

                $output->writeln("Envoyer un email à ". $mailTo);
                $mailer = $container->get('app.mailer');
                $body = $container->get('templating')->render(':email:test.email.twig');
                $sent = $mailer->sendToAdmin("Test email", $body, $mailTo);
                $output->writeln($sent ? "OK" : "NOK");
                break;
            default:
                $output->writeln("Commande '$cmd' inconnue");
                break;
        }
    }
}

?>

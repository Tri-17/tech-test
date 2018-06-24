<?php

namespace AppBundle\Command;

use AppBundle\Entity\Newsletter;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SendNewsletterCommand extends ContainerAwareCommand
{


    protected function configure()
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('app:newsletter:send')

            // the short description shown while running "php bin/console list"
            ->setDescription('send newsletter')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('Send a specific newsletter to subscribers')
            ->addArgument('newsletter', InputArgument::REQUIRED, 'The newsletter id to publish')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $newsletterId = $input->getArgument('newsletter');
        if (!$newsletter = $this->getContainer()->get('doctrine')->getManager()->getRepository(Newsletter::class)->find($newsletterId)) {
            $output.writeln('Newsletter id '.$newsletterId.' not found');
            exit;
        }


        $newsletterPublisher = $this->getContainer()->get('AppBundle\Services\NewsletterPublisher');
        $newsletterPublisher->publish($newsletter);
        // ...

        $output->writeln('Done');
    }
}
<?php

namespace Youshido\EncryptionBundle\Command;

use Doctrine\ORM\AbstractQuery;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DecryptCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('encryption:decrypt')
            ->addArgument('entity', InputArgument::REQUIRED)
            ->addArgument('field', InputArgument::REQUIRED)
            ->setDescription('Decrypt field for entity. This task must run only one time!');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $entity = $input->getArgument('entity');
        $field  = $input->getArgument('field');

        if(class_exists($entity)){
            $repository = $this->getContainer()->get('doctrine')->getRepository($entity);
            $this->encrypt($repository, $field);
        }else{
            $output->writeln('<error>Entity not exists</error>');
        }
    }

    private function encrypt($repository, $field)
    {
        $key = $this->getContainer()->getParameter('encryption.key');
        $manager = $this->getContainer()->get('encyption.manager');

        $items = $repository
            ->createQueryBuilder('e')
            ->select("e.id, e.{$field} as field")
            ->getQuery()
            ->getResult(AbstractQuery::HYDRATE_ARRAY);

        foreach($items as $item){
            $decodedValue = $manager->decodeValue($item['field'], $key);

            $repository
                ->createQueryBuilder('e')
                ->update()
                ->set('e.'.$field, ':value')
                ->where('e.id = :id')
                ->setParameter('value', $decodedValue)
                ->setParameter('id', $item['id'])
                ->getQuery()
                ->execute();
        }

    }
}

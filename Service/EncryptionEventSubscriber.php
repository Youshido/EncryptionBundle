<?php
/**
 * Date: 10.09.15
 *
 * @author Portey Vasil <portey@gmail.com>
 */

namespace Youshido\EncryptionBundle\Service;


use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreFlushEventArgs;

class EncryptionEventSubscriber implements EventSubscriber
{

    /** @var  EncryptionEntityManager */
    private $manger;

    public function __construct(EncryptionEntityManager $manager)
    {
        $this->manger = $manager;
    }

    /**
     * @inheritdoc
     */
    public function getSubscribedEvents()
    {
        return [
            'preFlush',
            'postLoad',
            'preUpdate'
        ];
    }

    public function preFlush(PreFlushEventArgs $args)
    {
        $em = $args->getEntityManager();
        $uow = $em->getUnitOfWork();

        foreach ($uow->getScheduledEntityInsertions() as $entity) {
            $this->manger->encodeEntity($entity);
        }
    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        $this->manger->encodeEntity($args->getObject());
    }

    public function postLoad(LifecycleEventArgs $args)
    {
        $this->manger->decodeEntity($args->getObject());
    }

}
<?php
/**
 * Date: 10.09.15
 *
 * @author Portey Vasil <portey@gmail.com>
 */

namespace Youshido\EncryptionBundle\Service;

use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Youshido\EncryptionBundle\Annotation\Holder\EncryptionHolder;
use Youshido\EncryptionBundle\Annotation\Reader\EncryptionReader;

class EncryptionEntityManager extends ContainerAware
{

    /** @var EncryptionReader */
    private $reader;

    public function __construct(EncryptionReader $reader)
    {
        $this->reader = $reader;
    }

    public function encodeEntity($entity)
    {
        $accessor = PropertyAccess::createPropertyAccessor();
        $holders = $this->reader->getHoldersOfEntity($entity);

        if ($holders) {
            foreach ($holders as $holder) {
                $encodedValue = $this->encodeValue($holder->getValue(), $this->getEncryptionKey($holder));
                $accessor->setValue($entity, $holder->getProperty(), $encodedValue);
            }
        }
    }

    private function getEncryptionKey(EncryptionHolder $holder)
    {
        if ($key = $holder->getAnnotation()->getEncryptionKey()) {
            return $key;
        }

        return $this->container->getParameter('encryption.key');
    }

    public function generateKey()
    {
        try {
            return base64_encode(\Crypto::createNewRandomKey());
        } catch (\Exception $e) {
            throw new \Exception('Can\'t generate encrypted key');
        }
    }

    public function decodeEntity($entity)
    {
        $accessor = PropertyAccess::createPropertyAccessor();
        $holders = $this->reader->getHoldersOfEntity($entity);

        if ($holders) {
            foreach ($holders as $holder) {
                $decodedValue = $this->decodeValue($holder->getValue(), $this->getEncryptionKey($holder));
                $accessor->setValue($entity, $holder->getProperty(), $decodedValue);
            }
        }
    }

    public function encodeValue($value, $key)
    {
        try {
            return base64_encode(\Crypto::encrypt($value, base64_decode($key)));
        } catch (\Exception $e) {
            throw new \Exception('Error while encoding data');
        }
    }

    public function decodeValue($value, $key)
    {
        try {
            return \Crypto::decrypt(base64_decode($value), base64_decode($key));
        } catch (\Exception $e) {
            throw new \Exception('Error while decoding data');
        }
    }
}
<?php
/**
 * Date: 10.09.15
 *
 * @author Portey Vasil <portey@gmail.com>
 */

namespace Youshido\EncryptionBundle\Annotation;


use Doctrine\Common\Annotations\Annotation;

/**
 * @Annotation
 * @Target({"PROPERTY"})
 */
final class Encryption extends Annotation
{

    public $encryptionKey = null;

    /**
     * @return string
     */
    public function getEncryptionKey()
    {
        return $this->encryptionKey;
    }

    /**
     * @param string $encryptionKey
     */
    public function setEncryptionKey($encryptionKey)
    {
        $this->encryptionKey = $encryptionKey;
    }
}
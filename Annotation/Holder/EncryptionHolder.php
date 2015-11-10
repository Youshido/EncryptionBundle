<?php
/**
 * Date: 10.09.15
 *
 * @author Portey Vasil <portey@gmail.com>
 */

namespace Youshido\EncryptionBundle\Annotation\Holder;


use Youshido\EncryptionBundle\Annotation\Encryption;


class EncryptionHolder
{
    /** @var  string */
    private $value;

    /** @var Encryption */
    private $annotation;

    /** @var  string */
    private $property;

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param string $value
     * @return EncryptionHolder
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @return string
     */
    public function getProperty()
    {
        return $this->property;
    }

    /**
     * @param string $property
     * @return EncryptionHolder
     */
    public function setProperty($property)
    {
        $this->property = $property;

        return $this;
    }

    /**
     * @return Encryption
     */
    public function getAnnotation()
    {
        return $this->annotation;
    }

    /**
     * @param Encryption $annotation
     * @return EncryptionHolder
     */
    public function setAnnotation($annotation)
    {
        $this->annotation = $annotation;

        return $this;
    }
}
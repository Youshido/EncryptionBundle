<?php
/**
 * Date: 10.09.15
 *
 * @author Portey Vasil <portey@gmail.com>
 */

namespace Youshido\EncryptionBundle\Annotation\Reader;


use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\DependencyInjection\ContainerAware;
use Youshido\EncryptionBundle\Annotation\Encryption;
use Youshido\EncryptionBundle\Annotation\Holder\EncryptionHolder;

class EncryptionReader extends ContainerAware
{

    private $annotationClass = 'Youshido\EncryptionBundle\Annotation\Encryption';

    /**
     * @param $entity
     * @return EncryptionHolder[]
     */
    public function getHoldersOfEntity($entity)
    {
        $reader = new AnnotationReader();

        $holders = [];

        if(method_exists($entity, '__getLazyProperties')){ //this object is proxy
            $reflectionObj = $this->container->get('doctrine')->getManager()
                ->getClassMetadata(get_class($entity))
                ->getReflectionClass();
        }else{
            $reflectionObj = new \ReflectionObject($entity);
        }


        try{
            do {
                foreach ($reflectionObj->getProperties() as $property) {
                    /** @var Encryption $annotation */
                    if ($annotation = $reader->getPropertyAnnotation($property, $this->annotationClass)) {
                        $property->setAccessible(true);
                        $value = $property->getValue($entity);

                        $holder = new EncryptionHolder();
                        $holder
                            ->setAnnotation($annotation)
                            ->setValue($value)
                            ->setProperty($property->getName());

                        $holders[] = $holder;
                    }
                }
            } while ($reflectionObj = $reflectionObj->getParentClass());
        } catch (\ReflectionException $e) { }

        return $holders;
    }

}
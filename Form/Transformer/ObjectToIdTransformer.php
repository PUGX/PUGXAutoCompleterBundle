<?php

namespace PUGX\AutocompleterBundle\Form\Transformer;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class ObjectToIdTransformer implements DataTransformerInterface
{
    private $om, $class;

    /**
     * @param ObjectManager $om
     * @param string        $class
     */
    public function __construct(ObjectManager $om, $class)
    {
        $this->om = $om;
        $this->class = $class;
    }

    /**
     * Transforms an object (object) to a string (id).
     *
     * @param  Object|null $object
     * @return string
     */
    public function transform($object)
    {
        if (null === $object) {
            return '';
        }

        return $object->getId();
    }

    /**
     * Transforms a string (id) to an object (object).
     *
     * @param  string $id
     * @return Object|null
     * @throws TransformationFailedException if object (object) is not found.
     */
    public function reverseTransform($id)
    {
        if (empty($id)) {
            return null;
        }
        $object = $this->om->getRepository($this->class)->find($id);
        if (null === $object) {
            throw new TransformationFailedException(sprintf('Object from class %s with id "%s" not found', $this->class, $id));
        }

        return $object;
    }
}
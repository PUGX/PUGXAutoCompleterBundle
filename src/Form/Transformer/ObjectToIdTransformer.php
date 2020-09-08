<?php

namespace PUGX\AutocompleterBundle\Form\Transformer;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class ObjectToIdTransformer implements DataTransformerInterface
{
    /**
     * @var ManagerRegistry
     */
    private $registry;

    /**
     * @var string
     */
    private $class;

    public function __construct(ManagerRegistry $registry, string $class)
    {
        $this->registry = $registry;
        $this->class = $class;
    }

    /**
     * Transforms an object (object) to a string (id).
     *
     * @param object|null $object
     */
    public function transform($object): string
    {
        if (null === $object) {
            return '';
        }

        return $object->getId();
    }

    /**
     * Transforms a string (id) to an object (object).
     *
     * @param string|int|null $id
     *
     * @throws TransformationFailedException if object (object) is not found
     */
    public function reverseTransform($id): ?object
    {
        if (empty($id)) {
            return null;
        }
        $object = $this->registry->getManagerForClass($this->class)->getRepository($this->class)->find($id);
        if (null === $object) {
            $msg = 'Object from class %s with id "%s" not found';
            throw new TransformationFailedException(\sprintf($msg, $this->class, $id));
        }

        return $object;
    }
}

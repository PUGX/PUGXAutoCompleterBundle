<?php

namespace PUGX\AutocompleterBundle\Form\Transformer;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class ObjectToIdTransformer implements DataTransformerInterface
{
    public function __construct(private ManagerRegistry $registry, private string $class)
    {
    }

    /**
     * Transforms an object (object) to a string (id).
     *
     * @param object|null $value
     */
    public function transform($value): string
    {
        if (null === $value) {
            return '';
        }

        return $value->getId();
    }

    /**
     * Transforms a string (id) to an object (object).
     *
     * @param string|int|null $value
     *
     * @throws TransformationFailedException if object (object) is not found
     */
    public function reverseTransform($value): ?object
    {
        if (empty($value)) {
            return null;
        }
        $object = $this->registry->getManagerForClass($this->class)->getRepository($this->class)->find($value);
        if (null === $object) {
            $msg = 'Object from class %s with id "%s" not found';
            throw new TransformationFailedException(\sprintf($msg, $this->class, $value));
        }

        return $object;
    }
}

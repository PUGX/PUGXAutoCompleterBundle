<?php

namespace PUGX\AutoCompleterBundle\Tests\Transformer;

use PHPUnit\Framework\TestCase;
use PUGX\AutocompleterBundle\Form\Transformer\ObjectToIdTransformer;
use PUGX\AutocompleterBundle\Tests\Stub\Entity;
use Symfony\Component\Form\Exception\TransformationFailedException;

final class ObjectToIdTransformerTest extends TestCase
{
    public function testTransform(): void
    {
        $registry = $this->getMockBuilder('Doctrine\Persistence\ManagerRegistry')->getMock();
        $object = new Entity();
        $class = 'foo';
        $transformer = new ObjectToIdTransformer($registry, $class);
        $this->assertEquals(42, $transformer->transform($object));
    }

    public function testTransformNull(): void
    {
        $registry = $this->getMockBuilder('Doctrine\Persistence\ManagerRegistry')->getMock();
        $class = 'foo';
        $transformer = new ObjectToIdTransformer($registry, $class);
        $this->assertEquals('', $transformer->transform(null));
    }

    public function testReverseTransform(): void
    {
        $registry = $this->getMockBuilder('Doctrine\Persistence\ManagerRegistry')->getMock();
        $om = $this->getMockBuilder('Doctrine\Persistence\ObjectManager')->getMock();
        $repository = $this->getMockBuilder('Doctrine\Persistence\ObjectRepository')->disableOriginalConstructor()->getMock();
        $class = 'foo';
        $object = new Entity();
        $transformer = new ObjectToIdTransformer($registry, $class);

        $registry->expects($this->once())->method('getManagerForClass')->willReturn($om);
        $om->expects($this->once())->method('getRepository')->willReturn($repository);
        $repository->expects($this->once())->method('find')->willReturn($object);

        $this->assertEquals($object, $transformer->reverseTransform($object));
    }

    public function testReverseTransformNull(): void
    {
        $registry = $this->getMockBuilder('Doctrine\Persistence\ManagerRegistry')->getMock();
        $class = 'foo';
        $transformer = new ObjectToIdTransformer($registry, $class);
        $this->assertNull($transformer->reverseTransform(null));
    }

    public function testReverseTransformException(): void
    {
        $this->expectException(TransformationFailedException::class);
        $registry = $this->getMockBuilder('Doctrine\Persistence\ManagerRegistry')->getMock();
        $om = $this->getMockBuilder('Doctrine\Persistence\ObjectManager')->getMock();
        $repository = $this->getMockBuilder('Doctrine\Persistence\ObjectRepository')->disableOriginalConstructor()->getMock();
        $class = 'foo';
        $object = new Entity();
        $transformer = new ObjectToIdTransformer($registry, $class);

        $registry->expects($this->once())->method('getManagerForClass')->willReturn($om);
        $om->expects($this->once())->method('getRepository')->willReturn($repository);
        $repository->expects($this->once())->method('find')->willReturn(null);

        $transformer->reverseTransform(42);
    }
}

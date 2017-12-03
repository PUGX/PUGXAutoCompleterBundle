<?php

namespace PUGX\AutoCompleterBundle\Tests\Transformer;

use PHPUnit\Framework\TestCase;
use PUGX\AutocompleterBundle\Form\Transformer\ObjectToIdTransformer;
use PUGX\AutocompleterBundle\Tests\Stub\Entity;
use Symfony\Component\Form\Exception\TransformationFailedException;

class ObjectToIdTransformerTest extends TestCase
{
    public function testTransform()
    {
        $registry = $this->getMockBuilder('Doctrine\Common\Persistence\ManagerRegistry')->getMock();
        $object = new Entity();
        $class = 'foo';
        $transformer = new ObjectToIdTransformer($registry, $class);
        $this->assertEquals(42, $transformer->transform($object));
    }

    public function testTransformNull()
    {
        $registry = $this->getMockBuilder('Doctrine\Common\Persistence\ManagerRegistry')->getMock();
        $class = 'foo';
        $transformer = new ObjectToIdTransformer($registry, $class);
        $this->assertEquals('', $transformer->transform(null));
    }

    public function testReverseTransform()
    {
        $registry = $this->getMockBuilder('Doctrine\Common\Persistence\ManagerRegistry')->getMock();
        $om = $this->getMockBuilder('Doctrine\Common\Persistence\ObjectManager')->getMock();
        $repository = $this->getMockBuilder('Doctrine\Common\Persistence\ObjectRepository')->disableOriginalConstructor()->getMock();
        $class = 'foo';
        $object = new Entity();
        $transformer = new ObjectToIdTransformer($registry, $class);

        $registry->expects($this->once())->method('getManagerForClass')->will($this->returnValue($om));
        $om->expects($this->once())->method('getRepository')->will($this->returnValue($repository));
        $repository->expects($this->once())->method('find')->will($this->returnValue($object));

        $this->assertEquals($object, $transformer->reverseTransform($object));
    }

    public function testReverseTransformNull()
    {
        $registry = $this->getMockBuilder('Doctrine\Common\Persistence\ManagerRegistry')->getMock();
        $class = 'foo';
        $transformer = new ObjectToIdTransformer($registry, $class);
        $this->assertNull($transformer->reverseTransform(null));
    }

    public function testReverseTransformException()
    {
        $this->expectException(TransformationFailedException::class);
        $registry = $this->getMockBuilder('Doctrine\Common\Persistence\ManagerRegistry')->getMock();
        $om = $this->getMockBuilder('Doctrine\Common\Persistence\ObjectManager')->getMock();
        $repository = $this->getMockBuilder('Doctrine\Common\Persistence\ObjectRepository')->disableOriginalConstructor()->getMock();
        $class = 'foo';
        $object = new Entity();
        $transformer = new ObjectToIdTransformer($registry, $class);

        $registry->expects($this->once())->method('getManagerForClass')->will($this->returnValue($om));
        $om->expects($this->once())->method('getRepository')->will($this->returnValue($repository));
        $repository->expects($this->once())->method('find')->will($this->returnValue(null));

        $transformer->reverseTransform(42);
    }
}

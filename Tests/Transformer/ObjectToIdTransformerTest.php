<?php

namespace PUGX\AutoCompleterBundle\Tests\Transformer;

use PUGX\AutocompleterBundle\Form\Transformer\ObjectToIdTransformer;
use PUGX\AutocompleterBundle\Tests\Stub\Entity;

class ObjectToIdTransformerTest extends \PHPUnit_Framework_TestCase
{
    public function testTransform()
    {
        $registry = $this->getMock('Doctrine\Common\Persistence\ManagerRegistry');
        $object = new Entity();
        $class = 'foo';
        $transformer = new ObjectToIdTransformer($registry, $class);
        $this->assertEquals(42, $transformer->transform($object));
    }

    public function testTransformNull()
    {
        $registry = $this->getMock('Doctrine\Common\Persistence\ManagerRegistry');
        $class = 'foo';
        $transformer = new ObjectToIdTransformer($registry, $class);
        $this->assertEquals('', $transformer->transform(null));
    }

    public function testReverseTransform()
    {
        $registry = $this->getMock('Doctrine\Common\Persistence\ManagerRegistry');
        $om = $this->getMock('Doctrine\Common\Persistence\ObjectManager');
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
        $registry = $this->getMock('Doctrine\Common\Persistence\ManagerRegistry');
        $class = 'foo';
        $transformer = new ObjectToIdTransformer($registry, $class);
        $this->assertNull($transformer->reverseTransform(null));
    }

    /**
     * @expectedException Symfony\Component\Form\Exception\TransformationFailedException
     */
    public function testReverseTransformException()
    {
        $registry = $this->getMock('Doctrine\Common\Persistence\ManagerRegistry');
        $om = $this->getMock('Doctrine\Common\Persistence\ObjectManager');
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

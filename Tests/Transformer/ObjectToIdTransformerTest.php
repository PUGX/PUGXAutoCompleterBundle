<?php

namespace PUGX\AutoCompleterBundle\Tests\Transformer;

use PUGX\AutocompleterBundle\Form\Transformer\ObjectToIdTransformer;
use PUGX\AutocompleterBundle\Tests\Stub\Entity;

class ObjectToIdTransformerTest extends \PHPUnit_Framework_TestCase
{
    public function testTransform()
    {
        $om = $this->getMock('Doctrine\Common\Persistence\ObjectManager');
        $object = new Entity();
        $class = 'foo';
        $transformer = new ObjectToIdTransformer($om, $class);
        $this->assertEquals(42, $transformer->transform($object));
    }

    public function testTransformNull()
    {
        $om = $this->getMock('Doctrine\Common\Persistence\ObjectManager');
        $class = 'foo';
        $transformer = new ObjectToIdTransformer($om, $class);
        $this->assertEquals('', $transformer->transform(null));
    }

    public function testReverseTransform()
    {
        $om = $this->getMock('Doctrine\Common\Persistence\ObjectManager');
        $repository = $this->getMockBuilder('Doctrine\Common\Persistence\ObjectRepository')->disableOriginalConstructor()->getMock();
        $class = 'foo';
        $object = new Entity();
        $transformer = new ObjectToIdTransformer($om, $class);

        $om->expects($this->once())->method('getRepository')->will($this->returnValue($repository));
        $repository->expects($this->once())->method('find')->will($this->returnValue($object));

        $this->assertEquals($object, $transformer->reverseTransform($object));
    }

    public function testReverseTransformNull()
    {
        $om = $this->getMock('Doctrine\Common\Persistence\ObjectManager');
        $class = 'foo';
        $transformer = new ObjectToIdTransformer($om, $class);
        $this->assertNull($transformer->reverseTransform(null));
    }

    /**
     * @expectedException Symfony\Component\Form\Exception\TransformationFailedException
     */
    public function testReverseTransformException()
    {
        $om = $this->getMock('Doctrine\Common\Persistence\ObjectManager');
        $repository = $this->getMockBuilder('Doctrine\Common\Persistence\ObjectRepository')->disableOriginalConstructor()->getMock();
        $class = 'foo';
        $object = new Entity();
        $transformer = new ObjectToIdTransformer($om, $class);

        $om->expects($this->once())->method('getRepository')->will($this->returnValue($repository));
        $repository->expects($this->once())->method('find')->will($this->returnValue(null));

        $transformer->reverseTransform(42);
    }
}
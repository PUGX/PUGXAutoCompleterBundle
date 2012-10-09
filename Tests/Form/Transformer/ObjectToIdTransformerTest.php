<?php

namespace PUGX\AutocompleterBundle\Tests\Form\Transformer;

use PUGX\AutocompleterBundle\Form\Transformer\ObjectToIdTransformer;
use PUGX\AutocompleterBundle\Tests\Stub\Entity;

class ObjectToIdTransformerTest extends \PHPUnit_Framework_TestCase
{
    public function testTransform()
    {
        $om = $this->getMockBuilder('Doctrine\Common\Persistence\ObjectManager')->disableOriginalConstructor()->getMock();
        $class = 'Foo';
        $entity = new Entity;
        $transformer = new ObjectToIdTransformer($om, $class);
        $this->assertEquals('', $transformer->transform(null));
        $this->assertEquals(42, $transformer->transform($entity));
    }

    public function testReverseTransform()
    {
        $om = $this->getMockBuilder('Doctrine\Common\Persistence\ObjectManager')->disableOriginalConstructor()->getMock();
        $class = 'Foo';
        $entity = new Entity;
        $repo = $this->getMockBuilder('Doctrine\Common\Persistence\ObjectRepository')->disableOriginalConstructor()->getMock();
        $transformer = new ObjectToIdTransformer($om, $class);
        $om->expects($this->exactly(1))->method('getRepository')->will($this->returnValue($repo));
        $repo->expects($this->exactly(1))->method('find')->with(42)->will($this->onConsecutiveCalls(true));
        $transformer->reverseTransform(42);
    }
}
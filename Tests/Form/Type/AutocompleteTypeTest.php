<?php

namespace PUGX\AutocompleterBundle\Tests\Form\Type;

use PUGX\AutocompleterBundle\Form\Type\AutocompleteType;
use PUGX\AutocompleterBundle\Tests\Stub\Entity;
use Symfony\Tests\Component\Form\FormInterface;

class AutocompleteTypeTest extends \PHPUnit_Framework_TestCase
{
    public function testBuildForm()
    {
        $om = $this->getMockBuilder('Doctrine\Common\Persistence\ObjectManager')->disableOriginalConstructor()->getMock();
        #$builder = $this->getMock('Symfony\Component\Form\FormBuilderInterface');
        $builder = new FormInterface;

        $transformer = $this->getMockBuilder('PUGX\AutocompleterBundle\Tests\Form\Transformer\ObjectToIdTransformer')->disableOriginalConstructor()->getMock();
        $transformer->expects($this->exactly(1))->method('__construct')->with($om, 'Foo');
        $type = new AutocompleteType($om);
        $options = array('class' => 'Foo');
        $type->buildForm($builder, $options);
    }

    public function testGetParent()
    {
        $om = $this->getMockBuilder('Doctrine\Common\Persistence\ObjectManager')->disableOriginalConstructor()->getMock();
        $type = new AutocompleteType($om);
        $this->assertEquals('text', $type->getParent());
    }

    public function testGetName()
    {
        $om = $this->getMockBuilder('Doctrine\Common\Persistence\ObjectManager')->disableOriginalConstructor()->getMock();
        $type = new AutocompleteType($om);
        $this->assertEquals('autocomplete', $type->getName());
    }
}
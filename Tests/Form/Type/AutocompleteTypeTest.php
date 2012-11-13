<?php

namespace PUGX\AutocompleterBundle\Tests\Form\Type;

use PUGX\AutocompleterBundle\Form\Type\AutocompleteType;
use PUGX\AutocompleterBundle\Tests\Stub\Entity;

class AutocompleteTypeTest extends \PHPUnit_Framework_TestCase
{
    public function testBuildForm()
    {
        $om = $this->getMockBuilder('Doctrine\Common\Persistence\ObjectManager')->disableOriginalConstructor()->getMock();
        $builder = $this->getMockBuilder('Symfony\Component\Form\FormBuilder')->disableOriginalConstructor()->getMock();
        $transformer = $this->getMockBuilder('PUGX\AutocompleterBundle\Tests\Form\Transformer\ObjectToIdTransformer')->disableOriginalConstructor()->getMock();
        $builder->expects($this->exactly(1))->method('addModelTransformer');

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

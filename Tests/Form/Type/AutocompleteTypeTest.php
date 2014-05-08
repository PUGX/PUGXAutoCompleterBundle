<?php

namespace PUGX\AutocompleterBundle\Tests\Form\Type;

use PUGX\AutocompleterBundle\Form\Type\AutocompleteType;
use PUGX\AutocompleterBundle\Tests\Stub\Entity;

class AutocompleteTypeTest extends \PHPUnit_Framework_TestCase
{
    public function testBuildForm()
    {
        $om = $this->getMock('Doctrine\Common\Persistence\ObjectManager');
        $builder = $this->getMockBuilder('Symfony\Component\Form\FormBuilder')->disableOriginalConstructor()->getMock();
        $transformer = $this->getMockBuilder('PUGX\AutocompleterBundle\Tests\Form\Transformer\ObjectToIdTransformer')->disableOriginalConstructor()->getMock();
        $builder->expects($this->exactly(1))->method('addModelTransformer');

        $type = new AutocompleteType($om);
        $options = array('class' => 'Foo');
        $type->buildForm($builder, $options);
    }

    /**
     * @expectedException Symfony\Component\Form\Exception\InvalidConfigurationException
     */
    public function testBuildFormException()
    {
        $om = $this->getMock('Doctrine\Common\Persistence\ObjectManager');
        $builder = $this->getMockBuilder('Symfony\Component\Form\FormBuilder')->disableOriginalConstructor()->getMock();

        $type = new AutocompleteType($om);
        $options = array();
        $type->buildForm($builder, $options);
    }

    public function testSetDefaultOptions()
    {
        $om = $this->getMock('Doctrine\Common\Persistence\ObjectManager');
        $resolver = $this->getMock('Symfony\Component\OptionsResolver\OptionsResolverInterface');
        $resolver->expects($this->once())->method('setDefaults');

        $type = new AutocompleteType($om);
        $type->setDefaultOptions($resolver);
    }

    public function testGetParent()
    {
        $om = $this->getMock('Doctrine\Common\Persistence\ObjectManager');
        $type = new AutocompleteType($om);
        $this->assertEquals('text', $type->getParent());
    }

    public function testGetName()
    {
        $om = $this->getMock('Doctrine\Common\Persistence\ObjectManager');
        $type = new AutocompleteType($om);
        $this->assertEquals('autocomplete', $type->getName());
    }
}

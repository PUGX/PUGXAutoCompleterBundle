<?php

namespace PUGX\AutocompleterBundle\Tests\Form\Type;

use PHPUnit\Framework\TestCase;
use PUGX\AutocompleterBundle\Form\Type\AutocompleteType;
use Symfony\Component\HttpKernel\Kernel;

class AutocompleteTypeTest extends TestCase
{
    public function testBuildForm()
    {
        $registry = $this->getMockBuilder('Doctrine\Common\Persistence\ManagerRegistry')->getMock();
        $builder = $this->getMockBuilder('Symfony\Component\Form\FormBuilder')->disableOriginalConstructor()->getMock();
        $transformer = $this->getMockBuilder('PUGX\AutocompleterBundle\Tests\Form\Transformer\ObjectToIdTransformer')->disableOriginalConstructor()->getMock();
        $builder->expects($this->exactly(1))->method('addModelTransformer');

        $type = new AutocompleteType($registry);
        $options = ['class' => 'Foo'];
        $type->buildForm($builder, $options);
    }

    public function testSetDefaultOptions()
    {
        $registry = $this->getMockBuilder('Doctrine\Common\Persistence\ManagerRegistry')->getMock();
        $resolver = $this->getMockBuilder('Symfony\Component\OptionsResolver\OptionsResolver')->getMock();
        $resolver->expects($this->once())->method('setDefaults');

        $type = new AutocompleteType($registry);
        $type->configureOptions($resolver);
    }

    public function testGetParent()
    {
        $registry = $this->getMockBuilder('Doctrine\Common\Persistence\ManagerRegistry')->getMock();
        $type = new AutocompleteType($registry);
        if (Kernel::VERSION_ID < 20800) {
            $this->assertEquals('text', $type->getParent());
        } else {
            $this->assertEquals('Symfony\Component\Form\Extension\Core\Type\TextType', $type->getParent());
        }
    }

    public function testGetBlockPrefix()
    {
        if (Kernel::VERSION_ID < 20800) {
            $this->markTestSkipped();
        }
        $registry = $this->getMockBuilder('Doctrine\Common\Persistence\ManagerRegistry')->getMock();
        $type = new AutocompleteType($registry);
        $this->assertEquals('autocomplete', $type->getBlockPrefix());
    }
}

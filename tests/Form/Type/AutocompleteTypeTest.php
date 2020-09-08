<?php

namespace PUGX\AutocompleterBundle\Tests\Form\Type;

use Doctrine\Persistence\ManagerRegistry;
use PHPUnit\Framework\TestCase;
use PUGX\AutocompleterBundle\Form\Type\AutocompleteType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class AutocompleteTypeTest extends TestCase
{
    public function testBuildForm(): void
    {
        /** @var ManagerRegistry&\PHPUnit\Framework\MockObject\MockObject $registry */
        $registry = $this->getMockBuilder(ManagerRegistry::class)->getMock();
        /** @var FormBuilder&\PHPUnit\Framework\MockObject\MockObject $builder */
        $builder = $this->getMockBuilder(FormBuilder::class)->disableOriginalConstructor()->getMock();
        $builder->expects(self::once())->method('addModelTransformer');

        $type = new AutocompleteType($registry);
        $options = ['class' => 'Foo'];
        $type->buildForm($builder, $options);
    }

    public function testSetDefaultOptions(): void
    {
        /** @var ManagerRegistry&\PHPUnit\Framework\MockObject\MockObject $registry */
        $registry = $this->getMockBuilder(ManagerRegistry::class)->getMock();
        /** @var OptionsResolver&\PHPUnit\Framework\MockObject\MockObject $resolver */
        $resolver = $this->getMockBuilder(OptionsResolver::class)->getMock();
        $resolver->expects(self::once())->method('setDefaults');

        $type = new AutocompleteType($registry);
        $type->configureOptions($resolver);
    }

    public function testGetParent(): void
    {
        /** @var ManagerRegistry&\PHPUnit\Framework\MockObject\MockObject $registry */
        $registry = $this->getMockBuilder(ManagerRegistry::class)->getMock();
        $type = new AutocompleteType($registry);
        self::assertEquals(TextType::class, $type->getParent());
    }

    public function testGetBlockPrefix(): void
    {
        /** @var ManagerRegistry&\PHPUnit\Framework\MockObject\MockObject $registry */
        $registry = $this->getMockBuilder(ManagerRegistry::class)->getMock();
        $type = new AutocompleteType($registry);
        self::assertEquals('autocomplete', $type->getBlockPrefix());
    }
}

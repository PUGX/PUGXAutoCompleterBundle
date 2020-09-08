<?php

namespace PUGX\AutoCompleterBundle\Tests\Transformer;

use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Persistence\ObjectRepository;
use PHPUnit\Framework\TestCase;
use PUGX\AutocompleterBundle\Form\Transformer\ObjectToIdTransformer;
use PUGX\AutocompleterBundle\Tests\Stub\Entity;
use Symfony\Component\Form\Exception\TransformationFailedException;

final class ObjectToIdTransformerTest extends TestCase
{
    public function testTransform(): void
    {
        /** @var ManagerRegistry&\PHPUnit\Framework\MockObject\MockObject $registry */
        $registry = $this->getMockBuilder(ManagerRegistry::class)->getMock();
        $object = new Entity();
        $class = 'foo';
        $transformer = new ObjectToIdTransformer($registry, $class);
        self::assertEquals(42, $transformer->transform($object));
    }

    public function testTransformNull(): void
    {
        /** @var ManagerRegistry&\PHPUnit\Framework\MockObject\MockObject $registry */
        $registry = $this->getMockBuilder(ManagerRegistry::class)->getMock();
        $class = 'foo';
        $transformer = new ObjectToIdTransformer($registry, $class);
        self::assertEquals('', $transformer->transform(null));
    }

    public function testReverseTransform(): void
    {
        /** @var ManagerRegistry&\PHPUnit\Framework\MockObject\MockObject $registry */
        $registry = $this->getMockBuilder(ManagerRegistry::class)->getMock();
        $om = $this->getMockBuilder(ObjectManager::class)->getMock();
        $repository = $this->getMockBuilder(ObjectRepository::class)->disableOriginalConstructor()->getMock();
        $class = 'foo';
        $object = new Entity();
        $transformer = new ObjectToIdTransformer($registry, $class);

        $registry->expects(self::once())->method('getManagerForClass')->willReturn($om);
        $om->expects(self::once())->method('getRepository')->willReturn($repository);
        $repository->expects(self::once())->method('find')->willReturn($object);

        self::assertEquals($object, $transformer->reverseTransform('42'));
    }

    public function testReverseTransformNull(): void
    {
        /** @var ManagerRegistry&\PHPUnit\Framework\MockObject\MockObject $registry */
        $registry = $this->getMockBuilder(ManagerRegistry::class)->getMock();
        $class = 'foo';
        $transformer = new ObjectToIdTransformer($registry, $class);
        self::assertNull($transformer->reverseTransform(null));
    }

    public function testReverseTransformException(): void
    {
        $this->expectException(TransformationFailedException::class);
        /** @var ManagerRegistry&\PHPUnit\Framework\MockObject\MockObject $registry */
        $registry = $this->getMockBuilder(ManagerRegistry::class)->getMock();
        $om = $this->getMockBuilder(ObjectManager::class)->getMock();
        $repository = $this->getMockBuilder(ObjectRepository::class)->disableOriginalConstructor()->getMock();
        $class = 'foo';
        $object = new Entity();
        $transformer = new ObjectToIdTransformer($registry, $class);

        $registry->expects(self::once())->method('getManagerForClass')->willReturn($om);
        $om->expects(self::once())->method('getRepository')->willReturn($repository);
        $repository->expects(self::once())->method('find')->willReturn(null);

        $transformer->reverseTransform(42);
    }
}

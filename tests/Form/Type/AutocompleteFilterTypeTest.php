<?php

namespace PUGX\AutocompleterBundle\Tests\Form\Type;

use Doctrine\Persistence\ManagerRegistry;
use PHPUnit\Framework\TestCase;
use PUGX\AutocompleterBundle\Form\Type\AutocompleteFilterType;

final class AutocompleteFilterTypeTest extends TestCase
{
    public function testGetBlockPrefix(): void
    {
        /** @var ManagerRegistry&\PHPUnit\Framework\MockObject\MockObject $registry */
        $registry = $this->getMockBuilder(ManagerRegistry::class)->getMock();
        $type = new AutocompleteFilterType($registry);
        self::assertEquals('filter_autocomplete', $type->getBlockPrefix());
    }
}

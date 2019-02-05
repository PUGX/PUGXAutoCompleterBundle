<?php

namespace PUGX\AutocompleterBundle\Tests\Form\Type;

use PHPUnit\Framework\TestCase;
use PUGX\AutocompleterBundle\Form\Type\AutocompleteFilterType;

final class AutocompleteFilterTypeTest extends TestCase
{
    public function testGetBlockPrefix(): void
    {
        $registry = $this->getMockBuilder('Doctrine\Common\Persistence\ManagerRegistry')->getMock();
        $type = new AutocompleteFilterType($registry);
        $this->assertEquals('filter_autocomplete', $type->getBlockPrefix());
    }
}

<?php

namespace PUGX\AutocompleterBundle\Tests\Listener;

use PHPUnit\Framework\TestCase;
use PUGX\AutocompleterBundle\Listener\FilterSubscriber;

final class FilterSubscriberTest extends TestCase
{
    public function testGetSubscribedEvents(): void
    {
        $subscriber = new FilterSubscriber();

        $this->assertCount(2, $subscriber->getSubscribedEvents());
    }

    public function testFilterAutocomplete(): void
    {
        $expr = $this->getMockBuilder('Doctrine\ORM\Query\Expr')->getMock();
        $expr->expects($this->once())->method('eq');
        $query = $this->getMockBuilder('Lexik\Bundle\FormFilterBundle\Filter\Doctrine\ORMQuery')
            ->disableOriginalConstructor()->getMock();
        $query->expects($this->once())->method('getExpr')->willReturn($expr);

        $event = $this->getMockBuilder('Lexik\Bundle\FormFilterBundle\Event\GetFilterConditionEvent')
            ->disableOriginalConstructor()->getMock();
        $event->expects($this->once())->method('getValues')->willReturn(['value' => 'foo']);
        $event->expects($this->any())->method('getField')->willReturn('baz');
        $event->expects($this->once())->method('getFilterQuery')->willReturn($query);

        $subscriber = new FilterSubscriber();
        $subscriber->filterAutocomplete($event);
    }
}

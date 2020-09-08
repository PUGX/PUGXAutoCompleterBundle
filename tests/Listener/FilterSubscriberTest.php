<?php

namespace PUGX\AutocompleterBundle\Tests\Listener;

use Doctrine\ORM\Query\Expr;
use Lexik\Bundle\FormFilterBundle\Event\GetFilterConditionEvent;
use Lexik\Bundle\FormFilterBundle\Filter\Doctrine\ORMQuery;
use PHPUnit\Framework\TestCase;
use PUGX\AutocompleterBundle\Listener\FilterSubscriber;

final class FilterSubscriberTest extends TestCase
{
    public function testGetSubscribedEvents(): void
    {
        $subscriber = new FilterSubscriber();

        self::assertCount(2, $subscriber::getSubscribedEvents());
    }

    public function testFilterAutocomplete(): void
    {
        $expr = $this->getMockBuilder(Expr::class)->getMock();
        $expr->expects(self::once())->method('eq');
        $query = $this->getMockBuilder(ORMQuery::class)->disableOriginalConstructor()->getMock();
        $query->expects(self::once())->method('getExpr')->willReturn($expr);

        /** @var GetFilterConditionEvent&\PHPUnit\Framework\MockObject\MockObject $event */
        $event = $this->getMockBuilder(GetFilterConditionEvent::class)->disableOriginalConstructor()->getMock();
        $event->expects(self::once())->method('getValues')->willReturn(['value' => 'foo']);
        $event->method('getField')->willReturn('baz');
        $event->expects(self::once())->method('getFilterQuery')->willReturn($query);

        $subscriber = new FilterSubscriber();
        $subscriber->filterAutocomplete($event);
    }
}

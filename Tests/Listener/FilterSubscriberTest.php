<?php

namespace PUGX\AutocompleterBundle\Tests\Listener;

use PUGX\AutocompleterBundle\Listener\FilterSubscriber;

class FilterSubscriberTest extends \PHPUnit_Framework_TestCase
{
    public function testGetSubscribedEvents()
    {
        $subscriber = new FilterSubscriber();

        $this->assertCount(2, $subscriber->getSubscribedEvents());
    }

    public function testFilterAutocomplete()
    {
        $expr = $this->getMock('Doctrine\ORM\Query\Expr');
        $expr->expects($this->once())->method('eq');
        $query = $this->getMockBuilder('Lexik\Bundle\FormFilterBundle\Filter\Doctrine\ORMQuery')
            ->disableOriginalConstructor()->getMock();
        $query->expects($this->once())->method('getExpr')->will($this->returnValue($expr));

        $event = $this->getMockBuilder('Lexik\Bundle\FormFilterBundle\Event\GetFilterConditionEvent')
            ->disableOriginalConstructor()->getMock();
        $event->expects($this->once())->method('getValues')->will($this->returnValue(array('value' => 'foo')));
        $event->expects($this->any())->method('getField')->will($this->returnValue('baz'));
        $event->expects($this->once())->method('getFilterQuery')->will($this->returnValue($query));

        $subscriber = new FilterSubscriber();
        $subscriber->filterAutocomplete($event);
    }
}

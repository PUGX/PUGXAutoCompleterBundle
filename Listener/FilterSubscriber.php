<?php

namespace PUGX\AutocompleterBundle\Listener;

use Lexik\Bundle\FormFilterBundle\Event\GetFilterConditionEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * See https://github.com/lexik/LexikFormFilterBundle for this custom filter.
 */
class FilterSubscriber implements EventSubscriberInterface
{
    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        if (!class_exists('Lexik\Bundle\FormFilterBundle\Event\GetFilterConditionEvent')) {
            return array();
        }

        return array(
            'lexik_form_filter.apply.orm.filter_autocomplete' => array('filterAutocomplete'),
            'lexik_form_filter.apply.dbal.filter_autocomplete' => array('filterAutocomplete'),
        );
    }

    /**
     * Apply a filter for a filter_autcomplete type.
     * This method should work whih both ORM and DBAL query builder.
     *
     * @param GetFilterConditionEvent $event
     */
    public function filterAutocomplete(GetFilterConditionEvent $event)
    {
        $expr = $event->getFilterQuery()->getExpr();
        $values = $event->getValues();

        if ('' !== $values['value'] && null !== $values['value']) {
            $paramName = str_replace('.', '_', $event->getField());
            $event->setCondition(
                $expr->eq($event->getField(), ':'.$paramName),
                array($paramName => $values['value'])
            );
        }
    }
}

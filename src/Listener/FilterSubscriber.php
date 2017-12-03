<?php

namespace PUGX\AutocompleterBundle\Listener;

use Lexik\Bundle\FormFilterBundle\Event\GetFilterConditionEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * See https://github.com/lexik/LexikFormFilterBundle for this custom filter.
 */
class FilterSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        if (!class_exists(GetFilterConditionEvent::class)) {
            return [];
        }

        return [
            'lexik_form_filter.apply.orm.filter_autocomplete' => ['filterAutocomplete'],
            'lexik_form_filter.apply.dbal.filter_autocomplete' => ['filterAutocomplete'],
        ];
    }

    /**
     * Apply a filter for a filter_autcomplete type.
     * This method should work whih both ORM and DBAL query builder.
     *
     * @param GetFilterConditionEvent $event
     */
    public function filterAutocomplete(GetFilterConditionEvent $event): void
    {
        $expr = $event->getFilterQuery()->getExpr();
        $values = $event->getValues();

        if ('' !== $values['value'] && null !== $values['value']) {
            $paramName = str_replace('.', '_', $event->getField());
            $event->setCondition(
                $expr->eq($event->getField(), ':'.$paramName),
                [$paramName => $values['value']]
            );
        }
    }
}

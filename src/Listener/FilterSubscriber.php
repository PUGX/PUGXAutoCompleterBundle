<?php

namespace PUGX\AutocompleterBundle\Listener;

use Lexik\Bundle\FormFilterBundle\Event\GetFilterConditionEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * See https://github.com/lexik/LexikFormFilterBundle for this custom filter.
 */
final class FilterSubscriber implements EventSubscriberInterface
{
    /**
     * @return array<string, array<string>>
     */
    public static function getSubscribedEvents(): array
    {
        if (!\class_exists(GetFilterConditionEvent::class)) {
            return [];
        }

        return [
            'lexik_form_filter.apply.orm.filter_autocomplete' => ['filterAutocomplete'],
            'lexik_form_filter.apply.dbal.filter_autocomplete' => ['filterAutocomplete'],
        ];
    }

    /**
     * Apply a filter for a filter_autcomplete type.
     * This method should work with both ORM and DBAL query builder.
     */
    public function filterAutocomplete(GetFilterConditionEvent $event): void
    {
        /** @var \Lexik\Bundle\FormFilterBundle\Filter\Doctrine\ORMQuery|\Lexik\Bundle\FormFilterBundle\Filter\Doctrine\DBALQuery $query */
        $query = $event->getFilterQuery();
        $expr = $query->getExpr();
        $values = $event->getValues();

        if ('' !== $values['value'] && null !== $values['value']) {
            $paramName = \str_replace('.', '_', $event->getField());
            $event->setCondition(
                $expr->eq($event->getField(), ':'.$paramName),
                [$paramName => $values['value']]
            );
        }
    }
}

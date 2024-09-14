<?php

namespace Doefom\StatamicTableOfContents\Modifiers;

use Doefom\StatamicTableOfContents\Classes\TocBuilder;
use Statamic\Modifiers\Modifier;
use Statamic\Support\Arr;

class TocIds extends Modifier
{
    /**
     * Modify a value.
     *
     * @param mixed $value The value to be modified
     * @param array $params Any parameters used in the modifier
     * @param array $context Contextual values
     * @return mixed
     */
    public function index($value, $params, $context)
    {
        $minLevel = Arr::get($params, 0, 1);
        $maxLevel = Arr::get($params, 1, 6);
        $ordered = Arr::get($params, 2, false);

        $tb = new TocBuilder($value);
        $tb->setMinLevel($minLevel)
            ->setMaxLevel($maxLevel)
            ->setOrdered($ordered);

        return $tb->addIdsToHeadings();
    }
}

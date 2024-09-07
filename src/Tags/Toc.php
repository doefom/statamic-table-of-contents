<?php

namespace Doefom\StatamicTableOfContents\Tags;

use Doefom\StatamicTableOfContents\Classes\TocBuilder;
use Statamic\Fields\Value;
use Statamic\Tags\Tags;

class Toc extends Tags
{
    /**
     * The {{ toc }} tag. Outputs a table of contents based on the headings in the html.
     */
    public function index(): string
    {
        $minLevel = $this->params->int('min');
        $maxLevel = $this->params->int('max');
        $ordered = $this->params->bool('ordered');

        // Try the 'content' parameter first, then fallback to 'content' in the context
        $content = $this->params->get('content', $this->context->get('content'));

        if (! $content) {
            return '';
        }

        // If the content is a value object (Bard), get the actual value. Otherwise, use the content as is (html).
        $html = $content instanceof Value ? $content->value() : $content;

        if (! $html) {
            return '';
        }

        $tb = new TocBuilder($html);
        if ($minLevel) {
            $tb->setMinLevel($minLevel);
        }
        if ($maxLevel) {
            $tb->setMaxLevel($maxLevel);
        }
        if ($ordered) {
            $tb->setOrdered($ordered);
        }

        return $tb->buildTocAsHtml();
    }
}

<?php

namespace Doefom\StatamicTableOfContents\Tags;

use Illuminate\Support\Str;
use Statamic\Fields\Value;
use Statamic\Support\Arr;
use Statamic\Tags\Tags;

class Toc extends Tags
{
    /**
     * The {{ toc }} tag. Outputs a table of contents based on the headings in the html.
     */
    public function index(): string
    {
        $minLevel = $this->params->int('min', 1);
        $maxLevel = $this->params->int('max', 6);
        $ordered = $this->params->bool('ordered', false);

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

        return $this->buildTocAsHtml($html, $minLevel, $maxLevel, $ordered);
    }

    /**
     * Build the table of contents as an ordered list.
     */
    public function buildTocAsHtml(string $html, int $minLevel, int $maxLevel, bool $ordered): string
    {
        $pattern = "/<h([$minLevel-$maxLevel])[^>]*>(.*?)<\/h[$minLevel-$maxLevel]>/";

        preg_match_all($pattern, $html, $matches);

        $nbMatches = count($matches[0]);

        if ($nbMatches === 0) {
            return '';
        }

        // Loop through the matches and extract the heading level and text
        $headings = [];
        for ($i = 0; $i < $nbMatches; $i++) {
            $headings[] = [
                'level' => intval($matches[1][$i]),
                'text' => strip_tags($matches[2][$i]),
            ];
        }

        // ------------------------------------------------------------------
        // Build the table of contents as an ordered list
        // ------------------------------------------------------------------

        $listTag = $ordered ? 'ol' : 'ul';
        $toc = "<$listTag>";

        $startLevel = collect($headings)->pluck('level')->min();
        $prev = $startLevel;

        foreach ($headings as $heading) {
            $level = Arr::get($heading, 'level');
            $text = Arr::get($heading, 'text');
            $slug = Str::slug($text);

            // Calculate depth change
            $depthChange = $level - $prev;

            // Close the previous list(s) if the current heading is of a lower level
            if ($depthChange < 0) {
                $toc .= str_repeat("</$listTag>", abs($depthChange));
            }

            // Open a new list(s) if the current heading is of a higher level
            if ($depthChange > 0) {
                $toc .= str_repeat("<$listTag>", $depthChange);
            }

            // Add the current heading to the list
            $toc .= '<li><a href="#'.$slug.'">'.$text.'</a></li>';

            // Update the previous level
            $prev = $level;
        }

        // Close all remaining open lists
        while ($prev >= $startLevel) {
            $toc .= "</$listTag>";
            $prev--;
        }

        // ------------------------------------------------------------------

        return $toc;
    }
}

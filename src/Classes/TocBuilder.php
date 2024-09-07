<?php

namespace Doefom\StatamicTableOfContents\Classes;

use Illuminate\Support\Collection;
use Statamic\Support\Arr;
use Statamic\Support\Str;

class TocBuilder
{
    protected string $html;

    protected int $minLevel = 1;

    protected int $maxLevel = 6;

    protected bool $ordered = false;

    public function __construct(?string $html = null)
    {
        $this->html = $html;
    }

    public function setMinLevel(int $minLevel): void
    {
        $this->minLevel = $minLevel;
    }

    public function setMaxLevel(int $maxLevel): void
    {
        $this->maxLevel = $maxLevel;
    }

    public function setOrdered(bool $ordered): void
    {
        $this->ordered = $ordered;
    }

    /**
     * Build the table of contents as HTML.
     */
    public function buildTocAsHtml(): string
    {
        $html = $this->html;
        $minLevel = $this->minLevel;
        $maxLevel = $this->maxLevel;
        $ordered = $this->ordered;
        $listTag = $ordered ? 'ol' : 'ul';

        $headings = $this->extractHeadings($html, $minLevel, $maxLevel);

        if ($headings->isEmpty()) {
            return '';
        }

        $startLevel = $headings->pluck('level')->min();
        $prev = $startLevel;

        // ------------------------------------------------------------------
        // Build the table of contents as an ordered list
        // ------------------------------------------------------------------

        $toc = "<$listTag>";

        foreach ($headings as $heading) {
            $level = Arr::get($heading, 'level');
            $text = Arr::get($heading, 'text');
            $slug = self::slugify($text);

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

    /**
     * Extract all headings from the HTML respecting the min and max level.
     *
     * @return Collection A collection of headings with 'level' and 'text' keys.
     */
    protected function extractHeadings(string $html, int $minLevel = 1, int $maxLevel = 6): Collection
    {
        $pattern = "/<h([$minLevel-$maxLevel])[^>]*>(.*?)<\/h[$minLevel-$maxLevel]>/";

        preg_match_all($pattern, $html, $matches);

        $nbMatches = count($matches[0]);

        // Loop through the matches and extract the heading level and text
        $headings = [];
        for ($i = 0; $i < $nbMatches; $i++) {
            $headings[] = [
                'level' => intval($matches[1][$i]),
                'text' => strip_tags($matches[2][$i]),
            ];
        }

        return collect($headings);
    }

    public static function slugify(string $text): string
    {
        return Str::slug(html_entity_decode($text));
    }
}

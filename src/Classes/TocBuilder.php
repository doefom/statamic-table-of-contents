<?php

namespace Doefom\StatamicTableOfContents\Classes;

use DOMDocument;
use DOMXPath;
use Illuminate\Support\Collection;
use Statamic\Support\Arr;
use Statamic\Support\Str;

class TocBuilder
{
    protected string $html;

    protected int $minLevel = 1;

    protected int $maxLevel = 6;

    protected bool $ordered = false;

    public function __construct(string $html)
    {
        $this->html = $html;
    }

    public function setMinLevel(int $minLevel): self
    {
        $this->minLevel = $minLevel;

        return $this;
    }

    public function setMaxLevel(int $maxLevel): self
    {
        $this->maxLevel = $maxLevel;

        return $this;
    }

    public function setOrdered(bool $ordered): self
    {
        $this->ordered = $ordered;

        return $this;
    }

    /**
     * Build the table of contents as HTML.
     */
    public function getTocMarkup(): string
    {
        $headings = $this->getHeadingsFormatted();

        if ($headings->isEmpty()) {
            return '';
        }

        $ordered = $this->ordered;

        $startLevel = $headings->pluck('level')->min();
        $prev = $startLevel;

        $listTag = $ordered ? 'ol' : 'ul';

        // ------------------------------------------------------------------
        // Build the table of contents as an ordered list
        // ------------------------------------------------------------------

        $toc = "<$listTag>";

        foreach ($headings as $heading) {
            $level = Arr::get($heading, 'level');
            $text = Arr::get($heading, 'text');
            $slug = Arr::get($heading, 'slug');

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

    public function addIdsToHeadings(): string
    {
        $doc = new DOMDocument;
        $doc->loadHTML($this->html);

        $xpath = new DOMXPath($doc);
        $headings = $xpath->query('//h1|//h2|//h3|//h4|//h5|//h6');

        $usedSlugs = collect();
        foreach ($headings as $heading) {
            $slug = $this->slugify($heading->textContent);
            $suffix = 1;

            while ($usedSlugs->contains($slug)) {
                $slug = $this->slugify($heading->textContent).'-'.$suffix;
                $suffix++;
            }

            $usedSlugs->add($slug);

            $heading->setAttribute('id', "$slug");
        }

        return $doc->saveHTML();
    }

    /**
     * Extract all headings from the HTML respecting the min and max level.
     *
     * @return Collection A collection of headings with 'level' and 'text' keys.
     */
    private function getHeadingsFormatted(): Collection
    {
        $minLevel = $this->minLevel;
        $maxLevel = $this->maxLevel;

        $doc = new DOMDocument;
        $doc->loadHTML($this->html);

        $xpath = new DOMXPath($doc);
        $range = collect(range($minLevel, $maxLevel));
        $expression = $range->map(fn ($level) => "//h$level")->implode('|'); // e.g. //h2|//h3|//h4

        $headingNodes = $xpath->query($expression);

        // Loop through the matches and extract the heading level and text
        $headings = collect();
        $usedSlugs = collect();
        foreach ($headingNodes as $headingNode) {
            $level = intval($headingNode->nodeName[1]);
            $text = $headingNode->textContent;
            $slug = $this->slugify($text);

            // Ensure the slug is unique or this table of contents
            $suffix = 1;
            while ($usedSlugs->contains($slug)) {
                $slug = $this->slugify($text).'-'.$suffix;
                $suffix++;
            }

            // Keep track of the used slugs
            $usedSlugs->add($slug);

            // Add the heading to the collection
            $headings->add([
                'level' => $level,
                'text' => $text,
                'slug' => $slug,
            ]);
        }

        return $headings;
    }

    private function slugify(string $text): string
    {
        return Str::slug(html_entity_decode($text));
    }
}

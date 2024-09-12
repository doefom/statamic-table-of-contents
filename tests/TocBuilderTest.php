<?php

namespace Doefom\StatamicTableOfContents\Tests;

use Doefom\StatamicTableOfContents\Classes\TocBuilder;

class TocBuilderTest extends TestCase
{
    // Increasing heading levels
    const HTML01 = <<<'HTML'
        <h1>Heading 01</h1>
        <h2>Heading 02</h2>
        <h3>Heading 03</h3>
        <h4>Heading 04</h4>
        <h5>Heading 05</h5>
        <h6>Heading 06</h6>
    HTML;

    // Decreasing heading levels
    const HTML02 = <<<'HTML'
        <h6>Heading 06</h6>
        <h5>Heading 05</h5>
        <h4>Heading 04</h4>
        <h3>Heading 03</h3>
        <h2>Heading 02</h2>
        <h1>Heading 01</h1>
    HTML;

    // Increasing and decreasing heading levels
    const HTML03 = <<<'HTML'
        <h1>Heading 01</h1>
        <h2>Heading 02</h2>
        <h3>Heading 03</h3>
        <h2>Heading 02</h2>
        <h1>Heading 01</h1>
    HTML;

    // Real world structure
    const HTML04 = <<<'HTML'
        <h2 id="heading-02">Heading 02</h2>
        <p>Some text after the first heading.</p>
        <ul><li><p>Has this bullet point</p></li><li><p>And this one</p></li></ul>
        <h3 id="heading-03">Heading 03</h3>
        <p>Then there's a heading of a higher level.</p>
        <h4 id="heading-04">Heading 04</h4><p>It has subcategories, so there's an even higher level.</p>
        <h4 id="heading-04">Heading 04</h4><p>This one is one of the subcategories.</p>
        <h2 id="heading-02">Heading 02</h2><p>And then at the end there's a whole other topic.</p>
    HTML;

    /**
     * Test TOC with increasing heading levels.
     */
    public function test_that_toc_matches_html_with_increasing_heading_levels(): void
    {
        $result = (new TocBuilder(self::HTML01))->getTocMarkup();
        $expected = '<ul><li><a href="#heading-01">Heading 01</a></li><ul><li><a href="#heading-02">Heading 02</a></li><ul><li><a href="#heading-03">Heading 03</a></li><ul><li><a href="#heading-04">Heading 04</a></li><ul><li><a href="#heading-05">Heading 05</a></li><ul><li><a href="#heading-06">Heading 06</a></li></ul></ul></ul></ul></ul></ul>';

        $this->assertEquals($expected, $result);
    }

    /**
     * Test TOC with decreasing heading levels.
     */
    public function test_that_toc_matches_html_with_decreasing_heading_levels(): void
    {
        $result = (new TocBuilder(self::HTML02))->getTocMarkup();
        $expected = '<ul><ul><ul><ul><ul><ul><li><a href="#heading-06">Heading 06</a></li></ul><li><a href="#heading-05">Heading 05</a></li></ul><li><a href="#heading-04">Heading 04</a></li></ul><li><a href="#heading-03">Heading 03</a></li></ul><li><a href="#heading-02">Heading 02</a></li></ul><li><a href="#heading-01">Heading 01</a></li></ul>';

        $this->assertEquals($expected, $result);
    }

    public function test_that_toc_matches_html_with_increasing_and_decreasing_heading_levels(): void
    {
        $result = (new TocBuilder(self::HTML03))->getTocMarkup();
        $expected = '<ul><li><a href="#heading-01">Heading 01</a></li><ul><li><a href="#heading-02">Heading 02</a></li><ul><li><a href="#heading-03">Heading 03</a></li></ul><li><a href="#heading-02">Heading 02</a></li></ul><li><a href="#heading-01">Heading 01</a></li></ul>';

        $this->assertEquals($expected, $result);
    }

    public function test_that_toc_matches_real_world_structure(): void
    {
        $result = (new TocBuilder(self::HTML04))->getTocMarkup();
        $expected = '<ul><li><a href="#heading-02">Heading 02</a></li><ul><li><a href="#heading-03">Heading 03</a></li><ul><li><a href="#heading-04">Heading 04</a></li><li><a href="#heading-04">Heading 04</a></li></ul></ul><li><a href="#heading-02">Heading 02</a></li></ul>';

        $this->assertEquals($expected, $result);
    }

    public function test_that_min_and_max_levels_can_be_set(): void
    {
        $builder = new TocBuilder(self::HTML04);
        $builder->setMinLevel(2);
        $builder->setMaxLevel(3);

        $result = $builder->getTocMarkup();
        $expected = '<ul><li><a href="#heading-02">Heading 02</a></li><ul><li><a href="#heading-03">Heading 03</a></li></ul><li><a href="#heading-02">Heading 02</a></li></ul>';

        $this->assertEquals($expected, $result);
    }

    public function test_that_toc_can_be_ordered(): void
    {
        $builder = new TocBuilder(self::HTML04);
        $builder->setOrdered(true);

        $result = $builder->getTocMarkup();
        $expected = '<ol><li><a href="#heading-02">Heading 02</a></li><ol><li><a href="#heading-03">Heading 03</a></li><ol><li><a href="#heading-04">Heading 04</a></li><li><a href="#heading-04">Heading 04</a></li></ol></ol><li><a href="#heading-02">Heading 02</a></li></ol>';

        $this->assertEquals($expected, $result);
    }

    public function test_empty_html(): void
    {
        $result = (new TocBuilder(''))->getTocMarkup();

        $this->assertEquals('', $result);
    }

    public function test_html_without_headings(): void
    {
        $result = new TocBuilder('<p>There are no headings in this HTML</p>');
        $result = $result->getTocMarkup();

        $this->assertEquals('', $result, 'HTML without headings should result in empty TOC');
    }

    public function test_malformed_html(): void
    {
        $this->markTestSkipped('Malformed HTML is not yet checked for.');
    }

    public function test_headings_with_special_characters_and_html_entities(): void
    {
        $html = '<h2>Some &amp; Others</h2><h3>A heading with "quotes" in it</h3>';
        $result = (new TocBuilder($html))->getTocMarkup();
        $expected = '<ul><li><a href="#some-others">Some &amp; Others</a></li><ul><li><a href="#a-heading-with-quotes-in-it">A heading with "quotes" in it</a></li></ul></ul>';

        $this->assertEquals($expected, $result);
    }

    public function test_single_heading(): void
    {
        $html = '<h2>Only heading in document</h2>';
        $result = (new TocBuilder($html))->getTocMarkup();
        $expected = '<ul><li><a href="#only-heading-in-document">Only heading in document</a></li></ul>';

        $this->assertEquals($expected, $result);
    }

    public function test_skipped_heading_levels(): void
    {
        $html = '<h2>H2</h2><h4>H4</h4><h6>H6</h6>';
        $result = (new TocBuilder($html))->getTocMarkup();
        $expected = '<ul><li><a href="#h2">H2</a></li><ul><ul><li><a href="#h4">H4</a></li><ul><ul><li><a href="#h6">H6</a></li></ul></ul></ul></ul></ul>';

        $this->assertEquals($expected, $result);
    }

    public function test_identical_headings(): void
    {
        // Working with identical headings will not crash the TOC builder but when clicking a link of one of the
        // duplicates, the browser will always scroll to the first one.
        $this->markTestSkipped('Identical headings are not yet checked for.');
    }
}

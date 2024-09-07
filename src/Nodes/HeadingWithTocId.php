<?php

namespace Doefom\StatamicTableOfContents\Nodes;

use Doefom\StatamicTableOfContents\Classes\TocBuilder;
use Tiptap\Nodes\Heading;

class HeadingWithTocId extends Heading
{
    public function renderHTML($node, $HTMLAttributes = []): array
    {
        // Add id attribute to heading when rendered by Bard
        $HTMLAttributes['id'] = TocBuilder::slugify($node->content[0]->text);

        return parent::renderHTML($node, $HTMLAttributes);
    }
}

<?php

namespace Doefom\StatamicTableOfContents\Nodes;

use Illuminate\Support\Str;
use Tiptap\Nodes\Heading;

class HeadingWithTocId extends Heading
{
    public function renderHTML($node, $HTMLAttributes = []): array
    {
        // Add id attribute to heading when rendered by Bard
        $HTMLAttributes['id'] = Str::slug($node->content[0]->text);

        return parent::renderHTML($node, $HTMLAttributes);
    }
}

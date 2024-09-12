<?php

namespace Doefom\StatamicTableOfContents;

use Doefom\StatamicTableOfContents\Nodes\HeadingWithTocId;
use Doefom\StatamicTableOfContents\Tags\Toc;
use Statamic\Fieldtypes\Bard\Augmentor;
use Statamic\Providers\AddonServiceProvider;

class ServiceProvider extends AddonServiceProvider
{
    protected $tags = [
        Toc::class,
    ];

    public function bootAddon()
    {}
}

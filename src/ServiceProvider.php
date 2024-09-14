<?php

namespace Doefom\StatamicTableOfContents;

use Doefom\StatamicTableOfContents\Modifiers\TocIds;
use Doefom\StatamicTableOfContents\Modifiers\Toc;
use Statamic\Providers\AddonServiceProvider;

class ServiceProvider extends AddonServiceProvider
{
    protected $modifiers = [
        Toc::class,
        TocIds::class,
    ];

    public function bootAddon() {}
}

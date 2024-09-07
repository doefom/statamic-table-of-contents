<?php

namespace Doefom\StatamicTableOfContents\Tests;

use Doefom\StatamicTableOfContents\ServiceProvider;
use Statamic\Testing\AddonTestCase;

abstract class TestCase extends AddonTestCase
{
    protected string $addonServiceProvider = ServiceProvider::class;
}

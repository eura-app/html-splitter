<?php

namespace David\HtmlSplitter\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \David\HtmlSplitter\HtmlSplitter
 */
class HtmlSplitter extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \David\HtmlSplitter\HtmlSplitter::class;
    }
}

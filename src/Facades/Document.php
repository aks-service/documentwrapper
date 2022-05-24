<?php

namespace AksService\DocumentWrapper\Facades;

use Illuminate\Support\Facades\Facade;

class Document extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'document';
    }
}

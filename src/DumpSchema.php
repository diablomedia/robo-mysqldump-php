<?php

namespace DiabloMedia\Robo\Task\MysqldumpPhp;

class DumpSchema extends Dump
{
    protected $defaultSettings = [
        'no-data'        => true,
        'add-drop-table' => true
    ];
}

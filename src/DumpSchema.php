<?php declare(strict_types=1);
namespace DiabloMedia\Robo\Task\MysqldumpPhp;

class DumpSchema extends Dump
{
    protected $defaultSettings = [
        'no-data'        => true,
        'add-drop-table' => true
    ];
}

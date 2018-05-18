<?php declare(strict_types=1);
namespace DiabloMedia\Robo\Task\MysqldumpPhp;

class DumpData extends Dump
{
    /**
     * @var array
     */
    protected $defaultSettings = [
        'add-drop-table' => true
    ];
}

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

    public function append(bool $append = true) : DumpData
    {
        if ($append === true) {
            $this->defaultSettings['add-drop-table'] = false;
            $this->defaultSettings['no-create-info'] = true;
            $this->defaultSettings['insert-ignore']  = true;
        }

        return $this;
    }
}

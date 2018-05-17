<?php

namespace DiabloMedia\Robo\Task\MysqldumpPhp;

use Exception;
use Ifsnop\Mysqldump\Mysqldump;
use Robo\Result;
use Robo\Task\BaseTask;

class Dump extends BaseTask
{
    protected $defaultSettings = [];

    /**
     * @var string
     */
    protected $dsn;

    /**
     * @var string;
     */
    protected $user;

    /**
     * @var string;
     */
    protected $pass;

    /**
     * @var string;
     */
    protected $file;

    /**
     * @var array
     */
    protected $dumpSettings = [];

    /**
     * @var array
     */
    protected $pdoSettings = [];

    public function __construct($dsn, $user, $pass)
    {
        $this->dsn  = $dsn;
        $this->user = $user;
        $this->pass = $pass;
    }

    public function withDumpSettings($dumpSettings)
    {
        $this->dumpSettings = $dumpSettings;

        return $this;
    }

    public function withPdoSettings($pdoSettings)
    {
        $this->pdoSettings = $pdoSettings;

        return $this;
    }

    public function toFile($file)
    {
        $this->file = $file;

        return $this;
    }

    protected function getMergedDumpSettings()
    {
        if (!empty(array_intersect_key($this->defaultSettings, $this->dumpSettings))) {
            return false;
        }

        return array_merge($this->defaultSettings, $this->dumpSettings);
    }

    public function run()
    {
        if (!class_exists('Ifsnop\Mysqldump\Mysqldump')) {
            return Result::errorMissingPackage($this, 'Mysqldump', 'ifsnop/mysqldump-php');
        }

        $dumpSettings = $this->getMergedDumpSettings();
        if ($dumpSettings === false) {
            $int = array_intersect_key($this->defaultSettings, $this->dumpSettings);
            return Result::error($this, 'Cannot override these settings on this task: ' . implode(', ', array_keys($int)));
        }

        if (empty($this->file)) {
            return Result::error($this, 'Filename must be specified with toFile method');
        }

        try {
            $dump = new Mysqldump(
                $this->dsn,
                $this->user,
                $this->pass,
                $dumpSettings,
                $this->pdoSettings
            );

            $this->printTaskInfo('Dumping data to ' . $this->file);

            $dump->start($this->file);
        } catch (Exception $e) {
            return Result::error($this, $e->getMessage());
        }

        return Result::success($this, 'Dumped to ' . $this->file);
    }
}

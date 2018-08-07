<?php declare(strict_types=1);
namespace DiabloMedia\Robo\Task\MysqldumpPhp;

use Robo\Common\BuilderAwareTrait;
use Robo\Contract\BuilderAwareInterface;
use Robo\Result;

class DumpDataPartial extends DumpData implements BuilderAwareInterface
{
    use BuilderAwareTrait;

    /**
     * @var array
     */
    protected $tableFilters         = [];

    /**
     * @var array
     */
    protected $tokens               = [];
    
    /**
     * @var int
     */
    protected $startingFileSequence = 1;

    /**
     * @var string
     */
    protected $dir;

    /**
     * @var array
     */
    protected $defaultSettings = [
        'add-drop-table' => true,
        'include-tables' => [],
        'exclude-tables' => [],
        'where'          => null
    ];

    public function withFilters(array $tableFilters) : DumpDataPartial
    {
        $this->tableFilters = $tableFilters;

        return $this;
    }

    public function startingFileSequence(string $seq) : DumpDataPartial
    {
        $this->startingFileSequence = (int) $seq;

        return $this;
    }

    public function toDir(string $dir) : DumpDataPartial
    {
        $this->dir = $dir;

        return $this;
    }

    public function run() : Result
    {
        $seq = $this->startingFileSequence;

        $collection = $this->collectionBuilder();

        foreach ($this->tableFilters as $table => $whereList) {
            $whereList  = (array)$whereList;
            $whereCount = 0;
            foreach ($whereList as $where) {
                foreach ($this->tokens as $token => $value) {
                    $where = str_replace($token, $value, $where);
                }

                $dumpSettings = $this->getMergedDumpSettings();
                if ($dumpSettings === false) {
                    $int = array_intersect_key($this->defaultSettings, $this->dumpSettings);
                    return Result::error($this, 'Cannot override these settings on this task: ' . implode(', ', array_keys($int)));
                }

                $dumpSettings['include-tables'] = [$table];
                $dumpSettings['where']          = $where;
                if ($whereCount > 0) {
                    // Ensure table creation is not performed if this is a continuation of a previous dump
                    $dumpSettings['add-drop-table'] = false;
                    $dumpSettings['no-create-info'] = true;
                }

                $filename = str_pad((string) $seq, strlen((string) $this->startingFileSequence), '0', STR_PAD_LEFT)
                    . '-' . $table
                    . (count($whereList) > 1 ? '-' . $whereCount : '')
                    . '.sql';
                $collection->taskDump($this->dsn, $this->user, $this->pass)
                    ->withDumpSettings($dumpSettings)
                    ->withPdoSettings($this->pdoSettings)
                    ->toFile($this->dir . DIRECTORY_SEPARATOR . $filename);

                $whereCount++;
            }

            $seq++;
        }

        return $collection->run();
    }
}

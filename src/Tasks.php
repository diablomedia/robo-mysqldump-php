<?php declare(strict_types=1);
namespace DiabloMedia\Robo\Task\MysqldumpPhp;

use Robo\Collection\CollectionBuilder;

trait Tasks
{
    protected function taskDump(string $dsn, string $user, string $pass) : CollectionBuilder
    {
        return $this->task(Dump::class, $dsn, $user, $pass);
    }

    protected function taskDumpSchema(string $dsn, string $user, string $pass) : CollectionBuilder
    {
        return $this->task(DumpSchema::class, $dsn, $user, $pass);
    }

    protected function taskDumpData(string $dsn, string $user, string $pass) : CollectionBuilder
    {
        return $this->task(DumpData::class, $dsn, $user, $pass);
    }

    protected function taskDumpDataPartial(string $dsn, string $user, string $pass) : CollectionBuilder
    {
        return $this->task(DumpDataPartial::class, $dsn, $user, $pass);
    }
}

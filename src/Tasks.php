<?php

namespace DiabloMedia\Robo\Task\MysqldumpPhp;

trait Tasks
{
    protected function taskDump($dsn, $user, $pass)
    {
        return $this->task(Dump::class, $dsn, $user, $pass);
    }

    protected function taskDumpSchema($dsn, $user, $pass)
    {
        return $this->task(DumpSchema::class, $dsn, $user, $pass);
    }

    protected function taskDumpData($dsn, $user, $pass)
    {
        return $this->task(DumpData::class, $dsn, $user, $pass);
    }

    protected function taskDumpDataPartial($dsn, $user, $pass)
    {
        return $this->task(DumpDataPartial::class, $dsn, $user, $pass);
    }
}

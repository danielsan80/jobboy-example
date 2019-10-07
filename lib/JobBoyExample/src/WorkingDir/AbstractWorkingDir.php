<?php

namespace JobBoyExample\WorkingDir;

abstract class AbstractWorkingDir implements WorkingDirInterface
{
    protected $workingDir;
    protected $checked=false;

    protected function ensureWorkingDirExists(): void
    {
        if ($this->checked) {
            return;
        }

        $this->doEnsureWorkingDirExists();

        $this->checked = true;
    }

    protected function doEnsureWorkingDirExists(): void
    {
        throw new \LogicException('Not implemented method');
    }

    public function get(): string
    {
        $this->ensureWorkingDirExists();
        return $this->workingDir;
    }

    public function clear(): void
    {
        $this->ensureWorkingDirExists();
        if (!$this->workingDir) {
            throw new \LogicException('This code should not be executed');
        }
        exec('rm -Rf '.$this->workingDir.'/*');
    }

    public function __toString()
    {
        return $this->get();
    }

}
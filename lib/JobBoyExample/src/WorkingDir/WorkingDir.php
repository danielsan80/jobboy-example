<?php

namespace JobBoyExample\WorkingDir;

class WorkingDir extends AbstractWorkingDir
{
    public function __construct($workingDir)
    {
        $this->workingDir = $workingDir;
    }

    protected function doEnsureWorkingDirExists(): void
    {
        if (DIRECTORY_SEPARATOR == $this->workingDir) {
            throw new \LogicException('Emh... nope');
        }

        if (!file_exists($this->workingDir)) {
            mkdir($this->workingDir, 0755, true);
        }
    }
}
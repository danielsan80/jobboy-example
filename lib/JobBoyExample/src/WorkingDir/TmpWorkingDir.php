<?php

namespace JobBoyExample\WorkingDir;

class TmpWorkingDir extends AbstractWorkingDir
{
    const DEFAULT_SUBDIR = '/working_dir';

    /** @var null|string */
    protected $subDir;

    public function __construct(?string $subDir = null)
    {
        $this->subDir = $subDir;
    }


    protected function doEnsureWorkingDirExists(): void
    {
        $subDir = $this->subDir;

        if (!$this->subDir) {
            $subDir = self::DEFAULT_SUBDIR;
        }

        $subDir = '/' . trim($subDir, '/');

        $this->workingDir = sys_get_temp_dir() . $subDir;

        if (!file_exists($this->workingDir)) {
            mkdir($this->workingDir, 0755, true);
        }
    }
}
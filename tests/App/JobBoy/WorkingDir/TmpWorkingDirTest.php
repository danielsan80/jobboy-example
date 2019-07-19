<?php

namespace Tests\App\JobBoy\WorkingDir;

use PHPUnit\Framework\TestCase;

use App\JobBoy\WorkingDir\TmpWorkingDir;

class TmpWorkingDirTest extends TestCase
{

    /**
     * @test
     */
    public function use_default_subdir()
    {

        rmdir(sys_get_temp_dir() . TmpWorkingDir::DEFAULT_SUBDIR);

        $workingDir = new TmpWorkingDir();
        $this->assertDirectoryNotExists(sys_get_temp_dir() . TmpWorkingDir::DEFAULT_SUBDIR);

        $workingDir->get();

        $this->assertDirectoryExists(sys_get_temp_dir() . TmpWorkingDir::DEFAULT_SUBDIR);

    }

    /**
     * @test
     */
    public function use_subdir()
    {

        rmdir(sys_get_temp_dir() . '/my/dir');
        rmdir(sys_get_temp_dir() . '/my');

        $workingDir = new TmpWorkingDir('/my/dir');
        $this->assertDirectoryNotExists(sys_get_temp_dir() . '/my/dir');

        $workingDir->get();

        $this->assertDirectoryExists(sys_get_temp_dir() . '/my/dir');

    }

}
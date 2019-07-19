<?php
namespace App\JobBoy\WorkingDir;


interface WorkingDirInterface
{
    public function get(): string;
    public function clear(): void;

    public function __toString();
}
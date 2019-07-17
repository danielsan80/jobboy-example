<?php

namespace App\JobBoy\CreateDataFile;

use JobBoy\Process\Domain\Entity\Id\ProcessId;
use JobBoy\Process\Domain\ProcessIterator\IterationResponse;
use JobBoy\Process\Domain\ProcessIterator\ProcessHandlers\UnhandledProcessHandler;

class Dummy extends UnhandledProcessHandler
{

    protected function doSupports(ProcessId $id): bool
    {
        return $this->process($id)->code() == 'create_data_file' &&
            !$this->process($id)->status()->isStarting();
    }


    public function handle(ProcessId $id): IterationResponse
    {
        if ($this->process($id)->status()->isFailing()) {
            $this->process($id)->changeStatusToFailed();
        } else {
            $this->process($id)->changeStatusToCompleted();
        }

        return new IterationResponse();
    }

}
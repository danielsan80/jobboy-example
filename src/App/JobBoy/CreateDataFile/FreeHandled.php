<?php

namespace App\JobBoy\CreateDataFile;

use JobBoy\Process\Domain\Entity\Id\ProcessId;
use JobBoy\Process\Domain\ProcessIterator\IterationResponse;
use JobBoy\Process\Domain\ProcessIterator\ProcessHandlers\HandledProcessHandler;

class FreeHandled extends HandledProcessHandler
{

    protected function doSupports(ProcessId $id): bool
    {
        return $this->process($id)->code() == 'create_data_file';
    }


    public function handle(ProcessId $id): IterationResponse
    {
        $this->process($id)->changeStatusToFailing();

        return new IterationResponse();
    }

}
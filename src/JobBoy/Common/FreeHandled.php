<?php

namespace App\JobBoy\Common;

use JobBoy\Process\Domain\Entity\Id\ProcessId;
use JobBoy\Process\Domain\ProcessHandler\IterationResponse;
use JobBoy\Process\Domain\ProcessHandler\ProcessHandlers\Base\AbstractHandledProcessHandler;

class FreeHandled extends AbstractHandledProcessHandler
{

    protected function doSupports(ProcessId $id): bool
    {
        return true;
    }


    public function handle(ProcessId $id): IterationResponse
    {
        $this->process($id)->changeStatusToFailing();

        return new IterationResponse();
    }

}
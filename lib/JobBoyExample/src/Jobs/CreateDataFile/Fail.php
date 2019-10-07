<?php

namespace JobBoyExample\Jobs\CreateDataFile;

use JobBoy\Process\Domain\Entity\Id\ProcessId;
use JobBoy\Process\Domain\ProcessHandler\IterationResponse;
use JobBoy\Process\Domain\ProcessHandler\ProcessHandlers\Base\AbstractUnhandledProcessHandler;

class Fail extends AbstractUnhandledProcessHandler
{

    protected function doSupports(ProcessId $id): bool
    {
        return $this->process($id)->code() == 'create_data_file' &&
            $this->process($id)->status()->isFailing();
    }


    public function handle(ProcessId $id): IterationResponse
    {
        $buffer = $this->process($id)->get('buffer');

        if ($buffer) {
            if (file_exists($buffer)) {
                @unlink($buffer);
            }

            if (file_exists($buffer)) {
                throw new \RuntimeException('I cannot remove the buffer file');
            }
        }


        $this->process($id)->changeStatusToFailed();

        return new IterationResponse();
    }

}
<?php

namespace App\JobBoy\CreateDataFile;

use JobBoy\Process\Domain\Entity\Id\ProcessId;
use JobBoy\Process\Domain\ProcessIterator\IterationResponse;
use JobBoy\Process\Domain\ProcessIterator\ProcessHandlers\Base\AbstractUnhandledProcessHandler;

class Finalize extends AbstractUnhandledProcessHandler
{

    protected function doSupports(ProcessId $id): bool
    {
        return $this->process($id)->code() == 'create_data_file' &&
            $this->process($id)->status()->isEnding();
    }


    public function handle(ProcessId $id): IterationResponse
    {
        $buffer = $this->process($id)->get('buffer');
        $filename = $this->process($id)->parameters()->get('filename');


        if (file_exists($filename)) {
            @unlink($filename);
        }

        if (file_exists($filename)) {
            throw new \RuntimeException('I cannot remove the old output file');
        }

        $cmd = sprintf('mv %s %s',
            $buffer,
            $filename
        );

        exec($cmd);

        if (!file_exists($filename)) {
            throw new \RuntimeException('The output file was not written');
        }

        $this->process($id)->changeStatusToCompleted();

        return new IterationResponse();
    }

}
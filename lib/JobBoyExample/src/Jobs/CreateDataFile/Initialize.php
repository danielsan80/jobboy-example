<?php

namespace JobBoyExample\Jobs\CreateDataFile;

use JobBoy\Process\Domain\Entity\Id\ProcessId;
use JobBoy\Process\Domain\ProcessHandler\IterationResponse;
use JobBoy\Process\Domain\ProcessHandler\ProcessHandlers\Base\AbstractUnhandledProcessHandler;
use JobBoy\Process\Domain\Repository\ProcessRepositoryInterface;
use JobBoyExample\WorkingDir\WorkingDirInterface;

class Initialize extends AbstractUnhandledProcessHandler
{

    /** @var WorkingDirInterface */
    protected $workingDir;

    public function __construct(ProcessRepositoryInterface $processRepository, WorkingDirInterface $workingDir)
    {
        parent::__construct($processRepository);
        $this->workingDir = $workingDir;
    }

    protected function doSupports(ProcessId $id): bool
    {
        return $this->process($id)->code()=='create_data_file' &&
            $this->process($id)->status()->isStarting();
    }


    public function handle(ProcessId $id): IterationResponse
    {
        if (!$this->process($id)->parameters()->has('filename')) {
            throw new \InvalidArgumentException('You should set the parameter `filename`');
        }

        $workingDir = $this->workingDir->get();

        $buffer = uniqid('buffer_');

        touch($workingDir . '/' . $buffer);

        $this->process($id)->set('written', 0);
        $this->process($id)->set('buffer', $workingDir . '/' . $buffer);
        $this->process($id)->changeStatusToRunning();

        return new IterationResponse();
    }

}
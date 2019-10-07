<?php

namespace JobBoyExample\Jobs\CreateDataFile;

use JobBoy\Process\Domain\Entity\Id\ProcessId;
use JobBoy\Process\Domain\ProcessHandler\IterationResponse;
use JobBoy\Process\Domain\ProcessHandler\ProcessHandlers\Base\AbstractUnhandledProcessHandler;

class Iterate extends AbstractUnhandledProcessHandler
{

    const ITERATION_SIZE = 500;
    const CHUNK_SIZE = 100;

    protected function doSupports(ProcessId $id): bool
    {
        return $this->process($id)->code() == 'create_data_file' &&
            $this->process($id)->status()->isRunning();
    }


    public function handle(ProcessId $id): IterationResponse
    {
        $total = $this->process($id)->parameters()->get('total', 1000);


        $faker = \Faker\Factory::create();

        $j = 0;
        $written = $this->process($id)->get('written');

        while ($written < $total) {

            $lines = [];
            $i = 0;
            while ($written + $i < $total) {

                $record = [
                    'firstname' => $faker->firstName(),
                    'lastname' => $faker->lastName,
                    'email' => $faker->email,
                    'phone' => $faker->phoneNumber,
                    'birth' => [
                        'date' => $faker->dateTimeThisCentury->format('Y-m-d'),
                        'city' => $faker->city,
                    ],
                    'bio' => $faker->text(400),
                    'address' => [
                        'street' => $faker->streetAddress,
                        'city' => $faker->city,
                        'postcode' => $faker->postcode,
                        'state' => $faker->state,
                    ],
                    'company' => [
                        'name' => $faker->company,
                        'catch_phrase' => $faker->catchPhrase,
                        'director' => $faker->name,
                    ]
                ];

                $lines[] = json_encode($record);

                $i++;
                $j++;

                if ($i >= self::CHUNK_SIZE) {
                    break;
                }

            }

            $buffer = $this->process($id)->get('buffer');

            $file = new \SplFileObject($buffer, 'a');
            $file->fwrite(implode(PHP_EOL, $lines) . PHP_EOL);


            $this->process($id)->inc('written', $i);
            $written = $this->process($id)->get('written');

            if ($written >= $total) {
                $this->process($id)->changeStatusToEnding();
            }

            if ($j >= self::ITERATION_SIZE) {
                break;
            }

        }

        return new IterationResponse();
    }

}
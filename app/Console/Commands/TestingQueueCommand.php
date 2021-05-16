<?php

namespace App\Console\Commands;

use App\Jobs\TestingQueue;
use Faker\Factory;
use Illuminate\Console\Command;

class TestingQueueCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'qtest:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test queue jobs';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $faker = Factory::create();
        for($i = 1; $i < 25; $i++) {
            TestingQueue::dispatch($faker->word);
        }
        TestingQueue::dispatch('Its over; All ok');

    }
}

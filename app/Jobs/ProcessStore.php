<?php

namespace App\Jobs;

use App\Helpers\StoreHelper;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;

class ProcessStore implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, StoreHelper;

    /**
     * CSV file
     *
     * @var
     */
    protected $csv;


    /**
     * Columns of $csv
     *
     * @var
     */
    protected $columns;


    /**
     * Store instance
     * @var
     */
    protected $store;

    /**
     * Create a new job instance.
     *
     * @param $csv
     * @param $columns
     * @param $store
     */
    public function __construct($csv, $columns, $store)
    {
        $this->csv     = $csv;
        $this->columns = $columns;
        $this->store   = $store;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            foreach ($this->csv as $row) {
                $this->check($row, $this->columns, $this->store);
            }
        } catch (\Exception $e) {
            Log::info("Failed process store: " . $e->getMessage());
        }
    }

    /**
     * Handle a job failure.
     *
     * @param  Throwable  $exception
     * @return void
     */
    public function failed(Throwable $exception)
    {
        Log::info("Failed process store: " . $exception->getMessage());
    }


}

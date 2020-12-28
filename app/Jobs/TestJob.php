<?php

namespace App\Jobs;

use App\Common\Exceptions\BaseException;

class TestJob extends Job
{
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        echo __CLASS__ . date('Y-m-d H:i:s') . "\r\n";
        throw new BaseException('error');
    }
}

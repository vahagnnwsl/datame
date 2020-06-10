<?php


namespace App\Console\Commands;


use Illuminate\Console\Command;

class TmpCommand extends Command
{
    protected $signature = 'tmp';

    protected $description = 'Temp command for tests';

    public function handle()
    {
        $this->info("test");
    }
}
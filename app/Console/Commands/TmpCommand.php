<?php


namespace App\Console\Commands;


use Illuminate\Console\Command;

class TmpCommand extends Command
{
    protected $signature = 'tmp {--file= : file path}';

    protected $description = 'Temp command for tests';

    public function handle()
    {
        $handle = fopen($this->option('file'), 'rb');
        $columns = fgets($handle);
        $this->info($columns);
        fclose($handle);
    }
}
<?php

namespace App\Console\Commands;

use App\Region;
use Illuminate\Console\Command;

class RegionsImport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'datame:import-regions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
     * @return mixed
     */
    public function handle()
    {
        $path = resource_path('files/okato.csv');
        $content = file_get_contents($path);

        $data = explode("\n", $content);
        for($i = 1; $i < count($data); $i++) {
            $line = $data[$i];
            if(!empty($line)) {
                $parts = explode(",", $line);

                $region = Region::where('kladr', $parts[1])->where('okato', $parts[2])->first();
                if(is_null($region)) {
                    $region = new Region();
                } else {
                    dump($line);
                }
                $region->kladr = $parts[1];
                $region->okato = $parts[2];
                $region->name = $parts[0];
                $region->save();
            }
        }

    }
}

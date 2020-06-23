<?php

namespace App\Console\Commands;

use App\Department;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CodeDepartmentsImport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'datame:import-code-department';

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
        $path = resource_path('files/code_departments.txt');
        $content = file_get_contents($path);

        $data = explode("\n", $content);

        for($i = 1; $i < count($data); $i++) {
            $line = $data[$i];
            if(!empty($line)) {
                $parts = explode("|", trim($line));
                if(count($parts) == 6) {
                    try {
                        $department = Department::where('system_id', $parts[1])->first();
                        if(is_null($department)) {
                            $department = new Department();
                        }
                        $department->system_id = $parts[1];
                        $department->code = $parts[2];
                        $department->name = $parts[3];

                        if(!empty(trim($parts[4]))) {
                            $dates = explode('.', $parts['4']);
                            $department->expire = Carbon::createFromDate($dates[2], $dates[1], $dates[0]);
                        }


                        $department->save();
                    } catch(\Exception $e) {
                        dump($e->getMessage());
                        dd($parts);
                    }
                }
            }
        }

    }
}

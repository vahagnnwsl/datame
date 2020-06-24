<?php


namespace App\Console\Commands;


use App\CustomData;
use App\Facades\DBBulkFacade;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class CustomDataImport extends Command
{
    protected $signature = 'datame:import-custom-data';

    protected $description = 'Import custom data files into custom_data table';

    private $bulkData = [];

    public function handle()
    {
        if (\App\CustomDataImport::processing()->exists())
        {
            $this->info("Terminating... Another import is running");
            exit();
        }
        $customDataImports = \App\CustomDataImport::new()->get();

        foreach ($customDataImports as $customDataImport) {
            $this->processImport($customDataImport);
        }
    }

    private function processImport(\App\CustomDataImport $customDataImport)
    {
        $this->processStarted($customDataImport);
        $this->bulkData = [];

        try {
            $i = 0;
            $iterator = $this->generateData($customDataImport);
            foreach ($iterator as $datum) {
                $this->bulkData[] = $datum;
                $i++;

                if (count($this->bulkData) >= 1000) {
                    $this->runBulk();
                    $this->info(round(memory_get_usage() / 1024 / 1024, 2) . ' MB');
                }
            }
            if (!empty($this->bulkData)) {
                $this->runBulk();
            }
            $this->processSuccess($customDataImport);
        } catch (\Exception $e) {
            $this->error($e->getMessage());
            $this->processFailed($customDataImport, $e->getMessage());
        }
    }

    private function runBulk()
    {
        try {
            $this->info("Running bulk of " . count($this->bulkData) . " items...");
            DBBulkFacade::insertOrUpdate('custom_data', $this->bulkData, ['additional']);
        } finally {
            $this->bulkData = [];
        }
    }

    private function mapData(array $data, array $map)
    {
        $newData = [];

        //ФИО
        $fullNameKey = array_search('full_name', $map);
        if ($fullNameKey === false) {
            $firstName = array_search('first_name', $map);
            $lastName = array_search('last_name', $map);
            $patronymic = array_search('patronymic', $map);
            $fullName = $data[$lastName] . " " . $data[$firstName] . " " . $data[$patronymic];
            unset($data[$lastName]);
            unset($data[$firstName]);
            unset($data[$patronymic]);
        } else {
            $fullName = $data[$fullNameKey];
            unset($data[$fullNameKey]);
        }
        $newData['full_name'] = $fullName;

        //Дата рождения
        $birthdayKey = array_search('birthday', $map);
        if ($birthdayKey === false) {
            $birthday = null;
        } else {
            $birthday = $data[$birthdayKey];
            unset($data[$birthdayKey]);
        }
        $newData['birthday'] = !$birthday ?: dt_parse($birthday);
        $newData['additional'] = json_encode($data);

        return $newData;
    }

    private function generateData(\App\CustomDataImport $customDataImport)
    {
        $delimiter = $customDataImport->delimiter;
        $columnsMap = $customDataImport->columns_map;

        $path = resource_path('files/custom_data_files/');
        $dataFilePath = $path . $customDataImport->file;
        $handle = fopen($dataFilePath, 'rb');

        try {
            $columns = convert(fgets($handle));
            $columns = trim($columns, $delimiter);
            $columns = explode($delimiter, $columns);
            $columns = array_slice($columns, 0, count($columns) - 1);

            if (count($columns) < 2) {
                throw new \Exception("Columns parse exception");
            }

            while ($line = fgets($handle)) {
                $line = convert($line);
                $line = trim($line);
                $line = trim($line, $delimiter);
                $line = explode($delimiter, $line);

                if (count($columns) == count($line)) {
                    $data = array_combine($columns, $line);
                    $result = $this->mapData($data, $columnsMap);
                    yield $result;
                }
            }
        } finally {
            fclose($handle);
        }
    }

    private function processFailed(\App\CustomDataImport $customDataImport, string $errorMessage)
    {
        $customDataImport->status = \App\CustomDataImport::STATUS_FAILED;
        $customDataImport->error_message = mb_substr($errorMessage,0, 500);
        $customDataImport->save();
    }

    private function processStarted(\App\CustomDataImport $customDataImport)
    {
        $customDataImport->status = \App\CustomDataImport::STATUS_PROCESSING;
        $customDataImport->save();
    }

    private function processSuccess(\App\CustomDataImport $customDataImport)
    {
        $customDataImport->status = \App\CustomDataImport::STATUS_SUCCESS;
        $customDataImport->save();
    }
}

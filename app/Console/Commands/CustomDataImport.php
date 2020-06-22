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

    public function handle()
    {
        $customDataImports = \App\CustomDataImport::new()->get();

        foreach ($customDataImports as $customDataImport) {
            $this->processImport($customDataImport);
        }
    }

    private function processImport(\App\CustomDataImport $customDataImport)
    {
        $this->processStarted($customDataImport);

        try {
            $i = 0;
            $bulkData = [];
            foreach ($this->generateData($customDataImport) as $datum) {
                $bulkData[] = $datum;
                $i++;
                $this->info("Added $i rows");

                if (count($bulkData) >= 1000) {
                    $this->runBulk($bulkData);
                    $bulkData = [];
                }
            }
            if (!empty($bulkData)) {
                $this->runBulk($bulkData);
            }
            $this->processSuccess($customDataImport);
        } catch (\Exception $e) {
            $this->error($e->getMessage());
            $this->processFailed($customDataImport, $e->getMessage());
        }
    }

    private function runBulk(array $data)
    {
        $this->info("Running bulk of " . count($data) . " items...");
        DBBulkFacade::insertOrUpdate('custom_data', $data, ['additional']);
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
        $newData['birthday'] = !$birthday?:dt_parse($birthday);
        $newData['additional'] = json_encode($data);

        return $newData;
    }

    private function generateData(\App\CustomDataImport $customDataImport)
    {
        $delimiter = $customDataImport->delimiter;

        $path = resource_path('files/custom_data_files/');
        $dataFilePath = $path . $customDataImport->file;
        $handle = fopen($dataFilePath, 'rb');

        try {
            $columns = fgets($handle);
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
                yield $this->mapData(array_combine($columns, $line), $customDataImport->columns_map);
            }
        } finally {
            fclose($handle);
        }
    }

    private function processFailed(\App\CustomDataImport $customDataImport, string $errorMessage)
    {
        $customDataImport->status = \App\CustomDataImport::STATUS_FAILED;
        $customDataImport->error_message = $errorMessage;
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
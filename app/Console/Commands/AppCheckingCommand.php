<?php

namespace App\Console\Commands;

use App\Events\AppEvent;
use App\Packages\Constants;
use App\Packages\Exceptions\AppNotExistException;
use App\Packages\Loggers\ApiLog;
use App\Packages\Repository\AppRepository;
use Carbon\Carbon;
use Illuminate\Console\Command;


class AppCheckingCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'datame:app-check {app_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Перезапуск проверки заявки';

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
     * @throws AppNotExistException
     */
    public function handle()
    {
        $appId = $this->argument('app_id');
        $logger = ApiLog::newInstance();
        $app = (new AppRepository($logger))->getAppById($appId);
        if(is_null($app)) {
            throw new AppNotExistException("Заявка с номером {$appId} не существует");
        }
        $logger->setIdentity($app->identity);

        if($app->status != 2) {
            $app->checkingList()->update([
                'message' => null,
                'status' => Constants::CHECKING_STATUS_NEW
            ]);

            $app->checking_date_last = Carbon::now();
            $app->save();

            $logger->info("Запуск проверки {$app->id} вручную командой.");

            event(new AppEvent($app));
        } else {
            $this->error("Заявка {$appId} уже поставлена в очередь на проверку");
        }

    }
}

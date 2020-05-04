<?php

namespace App\Console\Commands;

use App\App;
use App\CheckingList;
use App\Events\AppEvent;
use App\Notifications\SendAppPdfToUserNotification;
use App\Packages\Constants;
use App\Packages\Exceptions\AppNotExistException;
use App\Packages\Loggers\ApiLog;
use Carbon\Carbon;
use DB;
use Illuminate\Console\Command;


class AppSchedulerCheckingCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'datame:app-check-scheduler';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Отправляет заявки на проверкку, если не исчерпаны кол-во повторов заявок';

    protected $logger;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->logger = ApiLog::newInstance();
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @throws AppNotExistException
     */
    public function handle()
    {
        $this->runSchedulerHandler();
        $this->runStatusHandler();
    }

    protected function runSchedulerHandler() {

        //Выбираем заявки для обработки
        $apps = App::whereNotIn('status', [2, 4])->where('checking_count', '<', '3')->get();
        foreach($apps as $app) {
            //заявка обрабатывается впервые
            if(is_null($app->checking_date_next)) {
                $app->checking_count = 1;
                $app->checking_date_last = Carbon::now();
                $app->checking_date_next = Carbon::now()->addMinutes(10);
                $app->checkingList()->update([
                    'message' => null,
                    'status' => Constants::CHECKING_STATUS_NEW
                ]);
                $app->save();

                event(new AppEvent($app));
            } else {
                if($app->checking_date_next->lessThan(Carbon::now())) {
                    //увеличиваем время запуска
                    $app->checking_count += 1;
                    $app->checking_date_last = Carbon::now();
                    //устанавливаем следующее время проверки
                    switch($app->checking_count) {
                        case 2:
                            $app->checking_date_next = Carbon::now()->addMinutes(20);
                            break;
                        case 3:
                            $app->checking_date_next = Carbon::now()->addMinutes(30);
                            break;
                    }
                    $app->checkingList()->update([
                        'message' => null,
                        'status' => Constants::CHECKING_STATUS_NEW
                    ]);
                    $app->save();

                    event(new AppEvent($app));
                }
            }
        }
    }

    protected function runStatusHandler() {
        //Выбираем заявки находящиеся в обработке
        $apps = App::where('status', 2)->where('checking_count', '<', '4')->get();
        foreach($apps as $app) {

            $this->logger->setIdentity($app->identity);

            //если все проверки проведены устанавливаем конечный статус для заявки
            $allChecked = $app->checkingList()->get()->every(function(CheckingList $chk) {
                return $chk->status == Constants::CHECKING_STATUS_SUCCESS;
            });
            if($allChecked) {
                $this->logger->info("Заявка {$app->id} изменила статус на " . Constants::CHECKING_APP_SUCCESS);

                //заявка успешно обработана
                $app->status = Constants::CHECKING_APP_SUCCESS;
                $app->save();

                try {
                    $app->user->notify(new SendAppPdfToUserNotification($app));
                }catch (\Exception $exception){
                    $this->logger->info("ERROR " .$exception->getMessage() );

                }

                continue;
            }


            //если есть еще не обработанные сервисы
            $stillProcessing = $app->checkingList()
                ->whereIn('status', [Constants::CHECKING_STATUS_PROCESSING, Constants::CHECKING_STATUS_NEW])->count();
            if($stillProcessing > 0) {
                //проверим следующий раз
                continue;
            }


            //есть заявки с ошибками и кол-во попыток не больше 3
            $errors = $app->checkingList()->where('status', Constants::CHECKING_STATUS_ERROR)->count();
            if($errors > 0) {
                $this->logger->info("Заявка {$app->id} изменила статус на " . Constants::CHECKING_STATUS_ERROR);

                //заявка успешно обработана
                $app->status = Constants::CHECKING_STATUS_ERROR;
                $app->save();
                continue;
            }
        }

        //проверяем если нужно возместить кол-во проверяемых заявок
        $needReturnQuantity = App::where('status', 3)
            ->where('return_check_quantity', Constants::CHECK_QUANTITY_NEED_RETURN)
            ->where('checking_count', '3')
            ->get();

        foreach($needReturnQuantity as $app) {
            DB::transaction(function() use ($app) {
                $this->logger->info("check_quantity: " . $app->user->check_quantity);
                $app->user->check_quantity += 1;
                if($app->user->save()) {
                    $app->return_check_quantity = Constants::CHECK_QUANTITY_RETURNED;
                    if($app->save()) {
                        $this->logger->info("Заявка {$app->id} возмещена. check_quantity: " . $app->user->check_quantity);
                    };
                }
            });

        }
    }
}

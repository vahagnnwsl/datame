<?php

namespace App\Packages\Services;


use App\CheckingList;
use App\FindInn;
use App\HonestBusinessIp;
use App\HonestBusinessUl;
use App\Packages\Constants;
use App\Packages\Loggers\CustomLogger;
use App\Packages\Providers\HonestBusinessInformation;
use GuzzleHttp\Exception\GuzzleException;
use Throwable;

/**
 * Проверка на учредительство
 *
 * Class HonestBusinessCheckingService
 * @package App\Packages\Services
 */
class HonestBusinessCheckingService
{
    use TraitCheckingList;

    /** @var FindInn */
    protected $inn;
    protected $logger;

    public function __construct(FindInn $inn, CustomLogger $logger)
    {
        $this->inn = $inn;
        $this->logger = $logger;
    }

    /**
     * @return bool
     */
    public function execute()
    {
        /** @var CheckingList $checkingItem */
        $checkingItem = $this->getCheckingList($this->inn->app, CheckingList::ITEM_FIND_HONEST_BUSINESS);

        if ($checkingItem->status === Constants::CHECKING_STATUS_SUCCESS) {
            return true;
        }

        $this->setIsCheckedCheckingList($checkingItem, Constants::CHECKING_STATUS_PROCESSING);

        try {

            $this->logger->info("Старт проверки на учредительство", $this->inn->toArray());

            HonestBusinessIp::where('find_inn_id', $this->inn->id)->delete();
            HonestBusinessUl::where('find_inn_id', $this->inn->id)->delete();

            $information = (new HonestBusinessInformation($this->inn, $this->logger))->check();
            //проверка успешно проведена, сохраняем начисления
            foreach($information->getResult() as $item) {
                if($item['ТипДокумента'] == 'ul') {
                    $model = new HonestBusinessUl();
                    $model->find_inn_id = $this->inn->id;
                    $model->business_id = $item['id'];
                    $model->tip_document = $item['ТипДокумента'];
                    $model->naim_ul_sokr = $item['НаимЮЛСокр'];
                    $model->naim_ul_poln = $item['НаимЮЛПолн'];
                    $model->activnost = $item['Активность'];
                    $model->inn = $item['ИНН'];
                    $model->kpp = $item['КПП'];
                    $model->obr_data = dt_parse($item['ОбрДата']);
                    $model->adres = $item['Адрес'];
                    $model->kod_okved = $item['КодОКВЭД'];
                    $model->naim_okved = $item['НаимОКВЭД'];
                    $model->rukovoditel = implode(", ", $item['Руководитель']);
                    $model->save();
                } else if($item['ТипДокумента'] == 'ip') {
                    $model = new HonestBusinessIp();
                    $model->find_inn_id = $this->inn->id;
                    $model->business_id = $item['id'];
                    $model->tip_document = $item['ТипДокумента'];
                    $model->naim_vid_ip = $item['НаимВидИП'];
                    $model->familia = $item['Фамилия'];
                    $model->imia = $item['Имя'];
                    $model->otchestvo = $item['Отчество'];
                    $model->activnost = $item['Активность'];
                    $model->innfl = $item['ИННФЛ'];
                    $model->data_ogrnip = dt_parse($item['ДатаОГРНИП']);
                    $model->naim_stran = $item['НаимСтран'];
                    $model->kod_okved = $item['КодОКВЭД'];
                    $model->naim_okved = $item['НаимОКВЭД'];
                    $model->save();
                }
            }

            //отмечаем что проверка проведена
            $this->setIsCheckedCheckingList($checkingItem, Constants::CHECKING_STATUS_SUCCESS);

            $this->logger->info("Завершен поиск проверки на учредительство");

            return true;

        } catch(GuzzleException $e) {
            $this->logger->error("error: {$e->getMessage()}, {$e->getTraceAsString()}");
            $this->setError($checkingItem, $e->getMessage());
        } catch(Throwable $e) {
            $this->logger->error("error: {$e->getMessage()}, {$e->getTraceAsString()}");
            $this->setError($checkingItem, $e->getMessage());
        }

        return false;
    }
}

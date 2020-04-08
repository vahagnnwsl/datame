<?php

use App\Debtor;
use App\Disq;
use App\FedFsm;
use App\FindFssp;
use App\FindInn;
use App\FindTax;
use App\FsspWanted;
use App\HonestBusinessIp;
use App\HonestBusinessUl;
use App\InterpolRed;
use App\InterpolYellow;
use App\MvdWanted;
use App\Packages\Loggers\ApiLog;
use App\Packages\Repository\AppRepository;
use App\Passport;
use Illuminate\Database\Migrations\Migration;

class DemoAppCreation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //создаем заявку
        $data = [
            'lastname' => 'иванов',
            'name' => 'иван',
            'patronymic' => 'иванович',
            'birthday' => '23.01.1986',
            'passport_code' => '4508 123456',
            'date_of_issue' => '27.07.2006',
            'code_department' => "770-123"
        ];

        $logger = ApiLog::newInstance();
        $repository = new AppRepository($logger);

        $model = $repository->store(array_merge($data, ['ip' => "127.0.0.1", 'app_id_manual' => 1]), 1);

        //заполняем пасспорт
        $passport = new Passport();
        $passport->app_id = $model->id;
        $passport->is_valid = true;
        $passport->checking_state = Passport::STATE_SUCCESS;
        $passport->status = "Паспорт выдан вовремя";
        $passport->age14 = dt_parse("23.01.2000");
        $passport->age20 = dt_parse("23.01.2006");
        $passport->age45 = dt_parse("23.01.2031");
        $passport->passport_date_replace = dt_parse("23.0.1.2031");
        $passport->passport_serie_region = 1;
        $passport->passport_serie_year = 1;
        $passport->save();

        //инн
        $findInn = new FindInn();
        $findInn->app_id = $model->id;
        $findInn->type_inn = FindInn::INDIVIDUAL_INN;
        $findInn->inn = "771122334455";
        $findInn->save();

        //налоги
        $tax = new FindTax([
            'find_inn_id' => $findInn->id,
            'article' => "Транспортный налог с физических лиц (пени по соответствующему платежу)",
            'number' => "1234567890123890",
            'date_protocol' => dt_parse("14.04.2019"),
            'amount' => 67.63,
            'name' => "МРИ ФНС России №11 по Московской области",
            'inn' => "5043024703",
            'kpp' => "504301001",
            'okato' => "46770000",
            'bik' => "044525000",
            'rs' => "40101810845250010102",
            'kbk' => "18210604012022100110"
        ]);
        $tax->save();

        //фссп
        $findItem = new FindFssp();
        $findItem->app_id = $model->id;
        $findItem->fio = "Иванов Иван Иванович";
        $findItem->number = "11111/13/33/11 от 10.01.2019";
        $findItem->amount = 219564;
        $findItem->nazn = "Оплата задолженности по ИП № 11111/13/33/11 от 10.01.2019";
        $findItem->name_poluch = "УФК ПО Г.МОСКВЕ (ГАГАРИНСКИЙ ОСП УФССП РОССИИ ПО Г.МОСКВЕ Л/С 05731A53600)";
        $findItem->bik = "044525000";
        $findItem->rs = "40302810045251000079";
        $findItem->bank = "ГУ БАНКА РОССИИ ПО ЦФО";
        $findItem->kpp = "773645001";
        $findItem->inn = "7704270863";
        $findItem->date_protocol = dt_parse("10.01.2019");
        $findItem->contact = "Петров Д. П.+7(499)558-17-78+7(499)558-17-78";
        $findItem->save();

        $findItem = new FindFssp();
        $findItem->app_id = $model->id;
        $findItem->fio = "Иванов Иван Иванович";
        $findItem->number = "22222/14/33/11 от 10.02.2019";
        $findItem->amount = 0;
        $findItem->nazn = "Оплата задолженности по ИП № 22222/14/33/11 от 10.02.2019";
        $findItem->name_poluch = "УФК ПО Г.МОСКВЕ (ГАГАРИНСКИЙ ОСП УФССП РОССИИ ПО Г.МОСКВЕ Л/С 05731A53600)";
        $findItem->bik = "044525000";
        $findItem->rs = "40302810045251000079";
        $findItem->bank = "ГУ БАНКА РОССИИ ПО ЦФО";
        $findItem->kpp = "773645001";
        $findItem->inn = "7704270863";
        $findItem->date_protocol = dt_parse("10.02.2019");
        $findItem->contact = "Петров Д. П.+7(499)558-17-78+7(499)558-17-78";
        $findItem->save();

        $ul = new HonestBusinessUl();
        $ul->find_inn_id = $findInn->id;
        $ul->business_id = "1115030000040";
        $ul->tip_document = "1115030000040";
        $ul->naim_ul_sokr = 'ООО "ТТК ПЛЮС"';
        $ul->naim_ul_poln = 'ОБЩЕСТВО С ОГРАНИЧЕННОЙ ОТВЕТСТВЕННОСТЬЮ "TTK Плюс"';
        $ul->activnost = "Ликвидировано";
        $ul->inn = "1234567890";
        $ul->kpp = "773301001";
        $ul->obr_data = dt_parse("08.02.2019");
        $ul->adres = "123007, гор. Москва, ул. Академика Королева, д. 12";
        $ul->kod_okved = "45.1";
        $ul->naim_okved = "Торговля автотранспортными средствами";
        $ul->rukovoditel = "Генеральный директор Иванов Иван Иванович";
        $ul->save();

        $ip = new HonestBusinessIp();
        $ip->find_inn_id = $findInn->id;
        $ip->business_id = "317774600273293";
        $ip->tip_document = "ip";
        $ip->naim_vid_ip = "Индивидуальный предприниматель";
        $ip->familia = "Иванов";
        $ip->imia = "Иван";
        $ip->otchestvo = "Иванович";
        $ip->activnost = "Действующий";
        $ip->innfl = "12345678905";
        $ip->data_ogrnip = dt_parse("13.04.2004");
        $ip->naim_stran = "";
        $ip->kod_okved = "45.2";
        $ip->naim_okved = "Техническое обслуживание и ремонт автотранспортных средств";
        $ip->save();

        //Розыск
        $item = new FedFsm();
        $item->app_id = $model->id;
        $item->status = "В розыске отсутствует";
        $item->city_birth = "гор. Москва";
        $item->save();

        $item = new FsspWanted();
        $item->app_id = $model->id;
        $item->result = "В розыске отсутствует";
        $item->save();

        $item = new InterpolYellow();
        $item->app_id = $model->id;
        $item->result = "В розыске отсутствует";
        $item->save();

        $item = new InterpolRed();
        $item->app_id = $model->id;
        $item->result = "В розыске отсутствует";
        $item->save();

        $item = new MvdWanted();
        $item->app_id = $model->id;
        $item->result = "В розыске отсутствует";
        $item->save();

        $findItem = new Debtor();
        $findItem->find_inn_id = $findInn->id;
        $findItem->result = "Не является банкротом";
        $findItem->save();

        //дисквалифированное лицо
        $findItem = new Disq();
        $findItem->app_id = $model->id;
        $findItem->result = "Не является дисквалифицированным лицом";
        $findItem->save();

        $model->checkingList()->update([
            'status' => 4,
        ]);
        $model->status = 4;
        $model->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('apps')->delete(1);
    }
}

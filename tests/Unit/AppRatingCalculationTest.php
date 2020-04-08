<?php

namespace Tests\Unit;

use App\App;
use App\Packages\AppRatingCalculation;
use App\Packages\Transformer\AppTransformer;
use Tests\TestCase;

/**
 * Тест расчета рейтинга
 *
 *
 * Class AppRatingCalculationTest
 * @package Tests\Unit
 */
class AppRatingCalculationTest extends TestCase
{
    /**
     * Все сервисы с 3 статусом
     */
    public function testCalc_1()
    {
        $data = App::find(120);
        $result = (new AppRatingCalculation((new AppTransformer())->setExtend(true)->transform($data)))->calc();
        dd($result);
    }

    /**
     * Учредительство и ип
     */
    public function testCalc_2()
    {
        $data = App::find(66);
        $result = (new AppRatingCalculation((new AppTransformer())->setExtend(true)->transform($data)))->calc();
        dd($result);
    }

    /**
     * Налоги
     */
    public function testCalc_3()
    {
        $data = App::find(40);
        $result = (new AppRatingCalculation((new AppTransformer())->setExtend(true)->transform($data)))->calc();
        dd($result);
    }

    /**
     * Исполнительные производства
     */
    public function testCalc_4()
    {
        $data = App::find(9);
        $result = (new AppRatingCalculation((new AppTransformer())->setExtend(true)->transform($data)))->calc();
        dd($result);
    }

    /**
     * Банкрот
     */
    public function testCalc_5()
    {
        $data = App::find(104);
        $result = (new AppRatingCalculation((new AppTransformer())->setExtend(true)->transform($data)))->calc();
        dd($result);
    }

}

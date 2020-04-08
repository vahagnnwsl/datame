<?php

namespace Tests\Unit;

use App\Packages\Providers\TaxInformation;
use Tests\TestCase;

class TaxInformationTest extends TestCase
{
    /**
     * Поиск налогов по инн
     *
     * @return void
     */
    public function testTaxListTest()
    {
        $checker = new TaxInformation("500805218807");
        $response = $checker->check();

        dd($response->getResult());
        $this->assertTrue($response->getStatusResult());
    }

    /**
     * Поиск налогов по инн
     *
     * @return void
     */
    public function testTaxList1Test()
    {
        $checker = new TaxInformation("772982992699");
        $response = $checker->check();

        dd($response->getResult());
        $this->assertTrue($response->getStatusResult());
    }

    /**
     * Поиск налогов по инн
     *
     * @return void
     */
    public function testTaxList2Test()
    {
        $checker = new TaxInformation("770401554240");
        $response = $checker->check();

        dd($response->getResult());
        $this->assertTrue($response->getStatusResult());
    }

    /**
     * Поиск налогов по инн
     *
     * @return void
     */
    public function testTaxList3Test()
    {
        $checker = new TaxInformation("772982992699");
        $response = $checker->check();

        dd($response->getResult());
        $this->assertTrue($response->getStatusResult());
    }
}

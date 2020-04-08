<?php

namespace Tests\Unit;

use App\Packages\PassportValidChecker;
use Tests\TestCase;

class PassportValidTest extends TestCase
{
    /**
     * Проверка паспорта на действительность.
     *
     * @return void
     */
    public function testValidTest()
    {
        $series = "4007";
        $number = "138958";

        $checker = new PassportValidChecker();
        $result = $checker->check($series, $number);

        $this->assertFalse($result['status']);
        $this->assertTrue($result['message'] == 'Не действителен');
    }
}

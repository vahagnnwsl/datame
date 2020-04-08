<?php

namespace Tests\Feature\Api;

use Tests\TestCase;

class AppTest extends TestCase
{

    /**
     * Тестирование создание заявки
     *
     */
    public function testCreateApp_1()
    {

        $response = $this->post(route('api.apps.store'), [
            'lastname' => 'поликарова',
            'name' => 'евгения',
            'patronymic' => 'борисовна',
            'birthday' => '07.05.1969',
            'passport_code' => '4514 771950',
            'date_of_issue' => '31.05.2014',
            'code_department' => "772 002"
        ], [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . config('datame.test_api_token'),
        ]);

        $response->assertStatus(200);
    }

    public function testCreateApp_2()
    {

        $response = $this->post(route('api.apps.store'), [
            'lastname' => 'ЛЕВШАНОВ',
            'name' => 'АЛЕКСАНДР',
            'patronymic' => 'ПЕТРОВИЧ',
            'birthday' => '23.12.1964',
            'passport_code' => '4514 621950',
            'date_of_issue' => '31.05.2014',
            'code_department' => null
        ], [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . config('datame.test_api_token'),
        ]);
        $response->assertStatus(200);
    }

    /**
     * Тестирование заявки поиск в списках террористов и экстремистов
     */
    public function testCreateApp_3()
    {

        $response = $this->post(route('api.apps.store'), [
            'lastname' => 'ахмадов',
            'name' => 'адам',
            'patronymic' => 'эмиевич',
            'birthday' => '20.11.1986',
            'passport_code' => '4514 621950',
            'date_of_issue' => '31.05.2014',
            'code_department' => null
        ], [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . config('datame.test_api_token'),
        ]);
        $response->assertStatus(200);
    }

    /**
     * Тестирование интерпол красные карточки
     */
    public function testCreateApp_4()
    {
        $response = $this->post(route('api.apps.store'), [
            'lastname' => 'магуев',
            'name' => 'магомед',
            'patronymic' => 'эмиевич',
            'birthday' => '20.11.1986',
            'passport_code' => '4514 621950',
            'date_of_issue' => '31.05.2014',
            'code_department' => null
        ], [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . config('datame.test_api_token'),
        ]);
        $response->assertStatus(200);
    }

    /**
     * Тестирование поиск по дисквалицированным лицам
     */
    public function testDisqCreateApp()
    {
        $response = $this->post(route('api.apps.store'), [
            'lastname' => 'АБАБКОВА',
            'name' => 'МАРГАРИТА',
            'patronymic' => 'МИХАЙЛОВНА',
            'birthday' => '25.12.1957',
            'passport_code' => '4514 621950',
            'date_of_issue' => '31.05.2014',
            'code_department' => null
        ], [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . config('datame.test_api_token'),
        ]);
        $response->assertStatus(200);
    }

    /**
     * Тестирование интерпол желтые карточки
     */
    public function testCreateApp_5()
    {
        $response = $this->post(route('api.apps.store'), [
            'lastname' => 'абдаллах',
            'name' => 'амира',
            'patronymic' => 'эмиевич',
            'birthday' => '20.11.1986',
            'passport_code' => '4514 621950',
            'date_of_issue' => '31.05.2014',
            'code_department' => null
        ], [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . config('datame.test_api_token'),
        ]);
        $response->assertStatus(200);
    }


    public function testCreateApp_6()
    {
        $response = $this->post(route('api.apps.store'), [
            'lastname' => 'Бирюков',
            'name' => 'Кирилл',
            'patronymic' => 'Сергеевич',
            'birthday' => '21.06.1986',
            'passport_code' => '4005 986384',
            'date_of_issue' => '03.08.2006',
            'code_department' => null
        ], [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . config('datame.test_api_token'),
        ]);
        $response->assertStatus(200);
    }

    public function testCreateApp_7()
    {
        $response = $this->post(route('api.apps.store'), [
            'lastname' => 'Глинкин',
            'name' => 'Иван',
            'patronymic' => 'Олегович',
            'birthday' => '31.10.1987',
            'passport_code' => '4509 445343',
            'date_of_issue' => '19.12.2007',
            'code_department' => '770 069'
        ], [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . config('datame.test_api_token'),
        ]);
        $response->assertStatus(200);
    }

    public function testGetApps() {
        $response = $this->post(route('api.apps.filter'), [
            'searching' => ''
        ],[
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . config('datame.test_api_token'),
        ]);
        dd($response->content());
        $response->assertStatus(200);
    }

    public function testGetAppById() {

        $response = $this->get(route('api.apps.info', ['app_id' => 119]), [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . config('datame.test_api_token'),
        ]);

        dd(json_decode($response->content()));

    }
}

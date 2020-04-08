<?php

namespace Tests\Feature\Api;

use GuzzleHttp\Client;
use Tests\TestCase;

/**
 * Тестирование методов для регистрации/редактирования юридического лица
 *
 * Class LegalUserTest
 * @package Tests\Feature\Api
 */
class LegalUserTest extends TestCase
{
    public function testGetUsers()
    {

        $response = $this->post(route('api.users.legal', ['page' => 1, 'limit' => 2]), [],
            [
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . config('datame.test_api_token'),
            ]);
        $response->assertStatus(200);
    }

    public function testRegisterUser()
    {
        $response = $this->post(route('api.users.legal.register'), [
            'org' => 'org',
            'inn' => '1234567890',
            'ogrn' => '1234567890987',
            'manager' => 'manager',
            'director' => 'director11',
            'email' => 'test6@mail.ru',
            'phone' => '79855353130',
            'confirmed' => true,
            'password' => 'password',
            'password_confirmation' => 'password'
        ],
            [
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . config('datame.test_api_token'),
            ]
        );
        dd($response);
        $response->assertStatus(200);
    }

    public function testEditUser()
    {
        $response = $this->post(route('api.users.legal.edit'), [
            'id' => 4,
            'org' => 'org',
            'inn' => '1234567890',
            'ogrn' => '1234567890123',
            'director' => 'director',
            'manager' => 'manager',
            'email' => 'test2@mail.ru',
            'phone' => '79855353130',
            'confirmed' => true,
            'date_service' => '10.12.2018'
        ],
            [
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . config('datame.test_api_token'),
            ]
        );
        $response->assertStatus(200);
    }

    public function testConfirmData() {
        $response = $this->post(route('api.users.legal.confirm'), [
            'id' => 12,
        ],
            [
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . config('datame.test_api_token'),
            ]
        );
        $response->assertStatus(200);
    }
}

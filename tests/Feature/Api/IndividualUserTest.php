<?php

namespace Tests\Feature\Api;

use GuzzleHttp\Client;
use Tests\TestCase;

/**
 * Тестирование методов для регистрации/редактирования физическог лица
 *
 * Class IndividualUserTest
 * @package Tests\Feature\Api
 */
class IndividualUserTest extends TestCase
{
    public function testGetUsers()
    {

        $response = $this->post(route('api.users.individual', ['page' => 1, 'limit' => 2]), [],
            [
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . config('datame.test_api_token'),
            ]);
        $response->assertStatus(200);
    }

    public function testRegisterUser()
    {
        $response = $this->post(route('api.users.individual.register'), [
            'name' => 'Виталий',
            'email' => "test".rand(1, 1000)."@mail.ru",
            'lastname' => 'Корецкий-Виталий',
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
        $response = $this->post(route('api.users.individual.edit'), [
            'id' => 2,
            'name' => 'name2',
            'email' => 'test2@mail.ru',
            'lastname' => 'lastname2',
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
        $response = $this->post(route('api.users.individual.confirm'), [
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

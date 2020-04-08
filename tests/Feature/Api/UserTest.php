<?php

namespace Tests\Feature\Api;

use GuzzleHttp\Client;
use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * Тестирование информации о пользователе
     */
    public function testUser()
    {
        $response = $this->get(route('api.user'), [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . config('datame.test_api_token'),
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            "id",
            "name",
            "lastname",
            "email",
            "email_verified_at",
            "type_user",
            "phone",
            "inn",
            "ogrn",
            "director",
            "manager",
            "date_service",
            "check_quantity",
            "created_at",
            "updated_at",
        ]);
    }

    public function testPostIndividualErrorData()
    {
        $response = $this->post(route('api.user.individual.save'), [
            'name' => 'test name',
            'lastname' => 'lastname',
//            'phone' => '9855353130',
            'password' => '123456789',
            'password_confirmation' => '123456789'
        ], [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . config('datame.test_api_token'),
        ]);

        $response->assertStatus(422);
    }

    /**
     * Проверка сохранения данных физического лица
     */
    public function testPostIndividualData()
    {
        $response = $this->post(route('api.user.individual.save'), [
            'name' => 'test name',
            'lastname' => 'lastname',
            'phone' => '9855353130',
//            'password' => '123456789',
//            'password_confirmation' => '123456789'
        ], [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . config('datame.test_api_token'),
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'status' => true,
            'result' => 'Изменения вступят в силу после модерации.'
        ]);
    }

    public function testPostLegalData()
    {
        $response = $this->post(route('api.user.legal.save'), [
            'org' => 'test name',
            'inn' => 'inn',
            'ogrn' => 'ogrn',
            'director' => 'director',
            'manager' => 'manager',
            'phone' => '9855353130',
        ], [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . config('datame.test_api_token'),
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'status' => true,
            'result' => 'Изменения вступят в силу после модерации.'
        ]);
    }

    public function testPostPasswordChangeDate()
    {

        $response = $this->post(route('api.user.password.change'), [
            'current_password' => '$adminu44871',
            'new_password' => '123456789',
            'new_password_confirmation' => '123456789'
        ], [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . config('datame.test_api_token'),
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'status' => true,
            'result' => 'Изменения вступят в силу после модерации.'
        ]);
    }

}

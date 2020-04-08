<?php
/**
 * Created by PhpStorm.
 * User: won
 * Date: 2019-04-01
 * Time: 21:20
 */

namespace Tests\Feature\Api;


use Tests\TestCase;

class MessageTest extends TestCase
{
    public function testCreateForAllMessage_1()
    {

        $response = $this->post(route('api.messages.all.register.store'),
            [
                'message' => 'текст сообщения для зарегистированных пользователей',
                'message_type' => 1
            ],
            [
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . config('datame.test_api_token'),
            ]);

        $this->assertTrue($response->getStatusCode() == 200);
    }

    public function testCreateForAllMessage_2()
    {
        $response = $this->post(route('api.messages.all.unregister.store'),
            [
                'message' => 'текст сообщения для незарегистрированных пользователей',
                'message_type' => 2
            ],
            [
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . config('datame.test_api_token'),
            ]);

        $this->assertTrue($response->getStatusCode() == 200);
    }

    public function testCreateUserMessage()
    {
        $response = $this->post(route('api.messages.user.store'),
            [
                'message' => 'текст сообщения для пользователя с айди 2',
                'to_user_id' => 2
            ],
            [
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . config('datame.test_api_token'),
            ]);
        dd($response->content());
        $this->assertTrue($response->getStatusCode() == 200);
    }

    public function testGetMessages() {
        $response = $this->get(route('api.messages.list', [ 'page' => 1, 'limit' => 2]),
            [
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . config('datame.test_api_token'),
            ]);
dd($response->content());
        $this->assertTrue($response->getStatusCode() == 200);
    }
}
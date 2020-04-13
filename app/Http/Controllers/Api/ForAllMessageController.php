<?php
/**
 * Created by PhpStorm.
 * User: won
 * Date: 2019-04-01
 * Time: 21:15
 */

namespace App\Http\Controllers\Api;


use App\ForAllMessage;
use App\Http\Controllers\Controller;
use App\Packages\Constants;
use App\Packages\Loggers\ApiLog;
use App\User;
use App\UserMessage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;

class ForAllMessageController extends Controller
{
    protected $logger;

    public function __construct()
    {
        $this->logger = ApiLog::staticInstance();
    }

    public function deleteMessage($id)
    {
        ForAllMessage::destroy($id);
        return response()->json();

    }


    public function updateMessage(Request $request, $id)
    {
        $message = ForAllMessage::whereId($id)->first();

        if (!$message) {
            return response()->json(['message' => 'Сообщение не найдено'], 411);
        }

        $response = (new Error())->setIdentity($this->logger->getIdentity());

        $validator = Validator::make($request->all(), [
            'message' => ['required', 'string'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date']
        ]);

        if ($validator->fails()) {
            $response->setMessage($validator->errors()->first());
            $this->logger->error("error", $response->toArray());
            return response()->json($response, 422);
        }
        $message->start_date = dt_parse($validator->getData()['start_date']);
        $message->end_date = dt_parse($validator->getData()['end_date']);
        $message->message = $validator->getData()['message'];
        $message->save();
        return response()->json();

    }

    public function getMessages(Request $request, int $page = 1, int $limit = 10)
    {
        $builder = (new ForAllMessage())->orderByDesc('id');

        $total = $builder->count();
        $messages = $builder->skip(($page - 1) * $limit)->take($limit)->get()->transform(function (ForAllMessage $message) {
            return $this->messageTransform($message);
        });

        return response()->json((new PaginationResult($page, $limit, $total))->setItems($messages->toArray()));
    }

    public function messageTransform(ForAllMessage $forAllMessage)
    {
        return [
            'id' => $forAllMessage->id,
            'message' => $forAllMessage->message,
            'start_date' => format_date_time($forAllMessage->start_date),
            'end_date' => format_date_time($forAllMessage->end_date),
            'created_at' => format_date_time($forAllMessage->created_at)
        ];
    }

    /**
     * Создание общих сообщений для зарегистрированных пользователей
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function storeMessageRegisterUser(Request $request)
    {
        $response = (new Error())->setIdentity($this->logger->getIdentity());

        $validator = Validator::make($request->all(), [
            'message' => ['required', 'string'],
            'message_type' => ['required', Rule::in([Constants::MESSAGE_FOR_USER])]
        ]);

        if ($validator->fails()) {
            $response->setMessage($validator->errors()->first());
            $this->logger->error("error", $response->toArray());
            return response()->json($response, 422);
        }

        //Если сообщения для зарегистрированных пользователей
        //выбираем всех юзеров и добавляем им сообщения
        $users = User::whereNotIn('type_user', [Constants::USER_ADMIN])->get();
        foreach ($users as $user) {
            $message = new UserMessage();
            $message->message = $validator->getData()['message'];
            $message->is_read = false;
            $message->from_user_id = $request->user()->id;
            $message->to_user_id = $user->id;
            $message->save();
        }

        //создаем сообщения для всех пользователей
        $message = new ForAllMessage();
        $message->message = $validator->getData()['message'];
        $message->message_type = $validator->getData()['message_type'];
        $message->save();

        return response()->json();

    }


    /**
     * Создание общих сообщений для незарегистрированных пользователей
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function storeMessageUnRegisterUser(Request $request)
    {
        $response = (new Error())->setIdentity($this->logger->getIdentity());

        $dt = $request->all();
        $dt['start_date'] = isset($dt['start_date']) ? str_replace('_', '', $dt['start_date']) : null;
        $dt['end_date'] = isset($dt['end_date']) ? str_replace('_', '', $dt['end_date']) : null;

        $validator = Validator::make($dt, [
            'message' => ['required', 'string'],
            'start_date' => 'required|date_format:d.m.Y',
            'end_date' => 'required|date_format:d.m.Y',
            'message_type' => ['required', Rule::in([Constants::MESSAGE_NOT_FOR_USER])]
        ]);

        if ($validator->fails()) {
            $response->setMessage($validator->errors()->first());
            $this->logger->error("error", $response->toArray());
            return response()->json($response, 422);
        }

        //создаем сообщения для всех пользователей
        $message = new ForAllMessage();
        $message->message = $validator->getData()['message'];
        $message->message_type = $validator->getData()['message_type'];
        $message->start_date = dt_parse($validator->getData()['start_date']);
        $message->end_date = dt_parse($validator->getData()['end_date']);
        $message->save();

        return response()->json();

    }

    /**
     *
     */


}

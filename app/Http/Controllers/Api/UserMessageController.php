<?php
/**
 * Created by PhpStorm.
 * User: won
 * Date: 2019-04-01
 * Time: 21:15
 */

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Packages\Loggers\ApiLog;
use App\User;
use App\UserMessage;
use Carbon\Carbon;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Validator;

class UserMessageController extends Controller
{

    protected $logger;

    public function __construct()
    {
        $this->logger = ApiLog::staticInstance();
    }


    /**
     * Создание сообщений конкретному пользователю
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeMessage(Request $request)
    {
        $response = (new Error())->setIdentity($this->logger->getIdentity());

        $validator = Validator::make($request->all(), [
            'message' => ['required', 'string'],
            'to_user_id' => ['required', 'integer'],
        ]);

        if($validator->fails()) {
            $response->setMessage($validator->errors()->first());
            $this->logger->error("error", $response->toArray());
            return response()->json($response, 422);
        }

        $message = new UserMessage();
        $message->message = $validator->getData()['message'];
        $message->is_read = false;
        $message->from_user_id = $request->user()->id;
        $message->to_user_id = $validator->getData()['to_user_id'];
        $message->save();

        return response()->json($this->messageTransform($message));

    }

    public function getMessages(Request $request, int $page = 1, int $limit = 10)
    {
        /** @var Builder $builder */
        $builder = (new UserMessage())->orderByDesc('id')->where('to_user_id', $request->user()->id);

        $total = $builder->count();
        $messages = $builder->skip(($page - 1) * $limit)->take($limit)->get()->transform(function(UserMessage $message) {
            return $this->messageTransform($message);
        });

        return response()->json((new PaginationResult($page, $limit, $total))->setItems($messages->toArray()));
    }

    public function isReadMessage(Request $request, int $message_id)
    {
        $message = UserMessage::where('to_user_id', $request->user()->id)->where('id', $message_id)->first();
        if(is_null($message))
            abort(403);

        $message->is_read = true;
        $message->is_read_date = Carbon::now();
        $message->save();

        return $this->messageTransform($message);
    }

    public function messageTransform(UserMessage $message)
    {
        /** @var User $from */
        $from = $message->fromUser()->first();
        /** @var User $from */
        $to = $message->toUser()->first();

        return [
            'id' => $message->id,
            'from' => [
                'id' => $from->id,
                'name' => $from->getFullNameAttribute()
            ],
            'to' => [
                'id' => $to->id,
                'name' => $to->getFullNameAttribute()
            ],
            'is_read' => $message->is_read ? 1 : 0,
            'message' => $message->message,
            'created_at' => format_date_time($message->created_at)
        ];
    }


}
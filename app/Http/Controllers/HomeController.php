<?php

namespace App\Http\Controllers;

use App;
use App\ForAllMessage;
use App\Packages\Constants;
use App\Packages\Loggers\ApiLog;
use App\Packages\Repository\AppRepository;
use App\Packages\Transformer\AppTransformer;
use Auth;
use Barryvdh\Snappy\Facades\SnappyPdf as PDF;
use Cookie;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
//        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('index', [
            'message_for_all' => $this->getShowMessageForAll()
        ]);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function about()
    {
        return view('about', [
            'message_for_all' => $this->getShowMessageForAll()
        ]);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function faq()
    {
        return view('faq', [
            'message_for_all' => $this->getShowMessageForAll()
        ]);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function docApi()
    {
        return view('doc-api', [
            'message_for_all' => $this->getShowMessageForAll()
        ]);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function oferta()
    {
        return view('oferta', [
            'message_for_all' => $this->getShowMessageForAll()
        ]);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function tariff()
    {
        return view('tariff', [
            'message_for_all' => $this->getShowMessageForAll()
        ]);
    }

    public function tryApp(int $app_id)
    {
        return view('app-report-pdf', ['app_id' => $app_id]);
//       return view('app-report-pdf');
    }

    protected function getShowMessageForAll()
    {
        //если пользователь не зарегистрирован
        if(!Auth::check()) {
            //проверяем есть ли сообщения для не зарегистрированных пользователей

            $msg = ForAllMessage::select("*")->orderByDesc('id')
                ->whereRaw('? between start_date and end_date', [date('Y-m-d')])->first();

            if(!is_null($msg)) {
                $key = "message_for_all_{$msg->id}";
                if(is_null(Cookie::get($key))) {
                    Cookie::queue($key, trim($msg->message), 576000);
                    return trim($msg->message);
                }
            }
        }
        return "";
    }


    /**
     * Показывает страницу справки
     *
     * @param Request $request
     * @param $app_id
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function appReport(Request $request, $app_id)
    {
        $app = (new AppRepository(ApiLog::newInstance()))->getAppById($app_id);

        if(is_null($app))
            return response('Заявка не существует или доступ запрещен', 403);

        if($request->user()->type_user != Constants::USER_ADMIN && $app->user_id != $request->user()->id)
            return response('Заявка не существует или доступ запрещен', 403);

        return view('app-report', [
                'app_id' => $app_id,
                'app' => (new AppTransformer())->transform($app)
            ]
        );
    }

    /**
     * Показывает страницу справки
     *
     * @param $app_id
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function appReportDemo()
    {
        $app = (new AppRepository(ApiLog::newInstance()))->getAppById(1);

        return view('app-report', [
            'app_id' => $app->id,
            'app' => (new AppTransformer())->transform($app)
        ]);
    }


    public function loadPdf(Request $request, $identity, $font_td = 21, $font_name = 28, $font_stat = 18, $font_type='px')
    {

        $app = (new AppRepository(ApiLog::newInstance()))->getAppByIdentity($identity);

        if(is_null($app))
            return response('Заявка не существует или доступ запрещен', 403);

        if($app->id != 1) {
            if($request->user()->type_user != Constants::USER_ADMIN && $app->user_id != $request->user()->id)
                return response('Заявка не существует или доступ запрещен', 403);
        }


        //все проверки проведены успешно
//        if($app->status == Constants::CHECKING_STATUS_SUCCESS || ($app->status == Constants::CHECKING_STATUS_ERROR && $app->checking_count == 3)) {
        if($app->status !== Constants::CHECKING_STATUS_NEW) {

            $name = $app->created_at->format("Y.m.d")."-".mb_ucfirst($app->lastname)."_".mb_ucfirst($app->name)."_".mb_ucfirst($app->patronymic).".pdf";

            return $this->pdf($name, $identity, $font_td, $font_name, $font_stat, $font_type);

        } else {
            if($request->user()->type_user != Constants::USER_ADMIN && $app->user_id != $request->user()->id)
                return response('Заявка еще не обработана или доступ запрещен', 403);

            return response('Создать пдф для данной заявки нельзя', 400);
        }
    }

    public function pdf(string $name, string $identity, $font_td = 21, $font_name = 28, $font_stat = 18, $font_type = 'px')
    {

        return PDF::loadFile(route('app-report.template.pdf', [
            'identity' => $identity,
            'font_td' => $font_td,
            'font_name' => $font_name,
            'font_stat' => $font_stat,
            'font_type' => $font_type,
        ]))
            ->setOption('margin-left', 0)
            ->setOption('margin-right', 0)
            ->setOption('margin-bottom', 0)
            ->inline($name);
    }

    public function appTemplateForPdf($identity, $font_td = 21, $font_name = 28, $font_stat = 18, $font_type = 'px')
    {
        $app = (new AppRepository(ApiLog::newInstance()))->getAppByIdentity($identity);


        if(!is_null($app)) {
            $appTransform = (new AppTransformer())->setExtend(true)->transform($app);

            $data = [
                'app' => $appTransform,
                'service_error_message' => "Сервис не отвечает",
                'services' => $appTransform['services']['list'],
                'taxCount' => count($appTransform['extend']['tax']['items']),
                'taxAmount' => $appTransform['extend']['tax']['amount'],
                'fsspCountProceed' => count($appTransform['extend']['fssp']['proceed']),
                'fsspCountFinished' => count($appTransform['extend']['fssp']['finished']),
                'fsspAmount' => $appTransform['extend']['fssp']['amount'],
                'font_td' => $font_td,
                'font_name' => $font_name,
                'font_stat' => $font_stat,
                'font_type' => $font_type,
            ];




            return view('app-template-pdf', $data);
        } else {
            return response('Заявка не существует или доступ запрещен', 403);
        }
    }



}

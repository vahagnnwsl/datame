<?php
/**
 * Created by PhpStorm.
 * User: won
 * Date: 2019-02-03
 * Time: 00:19
 */

namespace App\Packages;


use App\App;

class Constants
{
    const USER_ADMIN = 1;
    const USER_INDIVIDUAL = 2;
    const USER_LEGAL = 3;

    //сообщения
    const MESSAGE_FOR_USER = 1;
    const MESSAGE_NOT_FOR_USER = 2;

    const CODE_DEPARTMENT_GUVM = 0;
    const CODE_DEPARTMENT_PVS = 1;
    const CODE_DEPARTMENT_PVS_REGION = 2;
    const CODE_DEPARTMENT_VILLAGE = 3;
    const CODE_DEPARTMENT_INCORRECT = 0;

    const CHECKING_STATUS_NEW = 1;
    const CHECKING_STATUS_PROCESSING = 2;
    const CHECKING_STATUS_ERROR = 3;
    const CHECKING_STATUS_SUCCESS = 4;


    const CHECKING_APP_NEW = 1;
    const CHECKING_APP_PROCESSING = 2;
    const CHECKING_APP_ERROR = 3;
    const CHECKING_APP_SUCCESS = 4;

    const CHECK_QUANTITY_NEED_RETURN = 1;
    const CHECK_QUANTITY_RETURNED = 2;

    public static function getDescAppStatus(App $app, $completed)
    {
        switch($app->status) {
            case Constants::CHECKING_APP_NEW:
                return "Ожидайте. Заявка поставлена в очередь на обработку!";
            case Constants::CHECKING_APP_PROCESSING:
                return "Ожидайте. Заявка находится в процессе обработки!";
            case Constants::CHECKING_APP_ERROR:
                if($app->checking_count < 3) {
                    return "Не удалось проверсти проверку полностью. Заявка снова поступит в очередь на обработку в " .  format_time($app->checking_date_next)." " .format_date($app->checking_date_next) . "!";
                } else {
                    //заявка обрабатывается только 3 раза
                    return "Заявка выполнена на {$completed}. Часть сервисов не отвечают!";
                }
            default:
                return "Заявка успешно обработана";
        }
    }

}
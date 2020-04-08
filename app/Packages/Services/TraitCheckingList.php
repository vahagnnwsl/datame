<?php
/**
 * Created by PhpStorm.
 * User: won
 * Date: 2019-02-22
 * Time: 11:24
 */

namespace App\Packages\Services;


use App\App;
use App\CheckingList;
use App\Packages\Constants;

trait TraitCheckingList
{

    public function getCheckingList(App $app, int $type)
    {
        $checkingItem = $app->checkingList()->where('type', $type)->first();
        $checkingItem->save();

        return $checkingItem;
    }

    public function setIsCheckedCheckingList(CheckingList $item, int $status)
    {
        $item->status = $status;
        if($item->status == Constants::CHECKING_APP_PROCESSING)
            $item->message = null;
        return $item->save();
    }

    public function setError(CheckingList $item, $message)
    {
        $item->status = Constants::CHECKING_STATUS_ERROR;
        $item->message = $message;
        return $item->save();
    }

    public function setMessage(CheckingList $item, $message)
    {
        $item->status = Constants::CHECKING_STATUS_SUCCESS;
        $item->message = $message;
        return $item->save();
    }

}
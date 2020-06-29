<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\CheckingList
 *
 * @property int $id
 * @property int $app_id
 * @property int $type
 * @property int $status
 * @property string|null $message
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CheckingList newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CheckingList newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CheckingList query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CheckingList whereAppId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CheckingList whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CheckingList whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CheckingList whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CheckingList whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CheckingList whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CheckingList whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CheckingList extends Model
{
    const ITEM_PASSPORT = 1;
    const ITEM_FIND_INN = 2;
    const ITEM_FIND_TAX = 3;
    const ITEM_FIND_FSSP = 4;
    const ITEM_FIND_INTERPOL_RED = 5;
    const ITEM_FIND_INTERPOL_YELLOW = 6;
    const ITEM_FIND_TERRORIST = 7;
    const ITEM_FIND_MVD_WANTED = 8;
    const ITEM_FIND_FSSP_WANTED = 9;
    const ITEM_FIND_DISQ = 10;
    const ITEM_FIND_DEBTOR = 11;
    const ITEM_FIND_HONEST_BUSINESS = 12;
    const ITEM_FIND_CODE_DEPARTMENT = 13;
    const ITEM_FIND_FSIN = 14;
    const ITEM_FIND_CUSTOM_DATA = 15;
    const ITEM_FIND_FT_SERVICE = 16;

    private static $services = [
        CheckingList::ITEM_PASSPORT,
        CheckingList::ITEM_FIND_INN,
        CheckingList::ITEM_FIND_TAX,
        CheckingList::ITEM_FIND_FSSP,
        CheckingList::ITEM_FIND_INTERPOL_RED,
        CheckingList::ITEM_FIND_INTERPOL_YELLOW,
        CheckingList::ITEM_FIND_TERRORIST,
        CheckingList::ITEM_FIND_MVD_WANTED,
        CheckingList::ITEM_FIND_FSSP_WANTED,
        CheckingList::ITEM_FIND_DISQ,
        CheckingList::ITEM_FIND_DEBTOR,
        CheckingList::ITEM_FIND_HONEST_BUSINESS,
        CheckingList::ITEM_FIND_CODE_DEPARTMENT,
        CheckingList::ITEM_FIND_FSIN,
        CheckingList::ITEM_FIND_CUSTOM_DATA,
        CheckingList::ITEM_FIND_FT_SERVICE
    ];

    protected $guarded = [];

    public static function getServices()
    {
        return self::$services;
    }
}

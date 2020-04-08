<?php
/**
 * Created by PhpStorm.
 * User: lodar
 * Date: 12.07.16
 * Time: 23:10
 */

namespace App\Packages\Moneta\request;

use App\Http\Controllers\Moneta\types\FieldsInfo;

class GetNextStepRequest
{
    /**
     * @var int
     */
    public $providerId;
    /**
     * @var string
     */
    public $currentStep;
    /**
     * @var FieldsInfo
     */
    public $fieldsInfo;

}
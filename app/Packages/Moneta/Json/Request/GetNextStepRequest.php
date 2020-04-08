<?php
/**
 * Created by PhpStorm.
 * User: won
 * Date: 2019-02-21
 * Time: 16:33
 */

namespace App\Packages\Moneta\Json\Request;

/**
 * Запрос списка начислений
 *
 * Class GetNextStepRequest
 * @package App\Packages\Moneta\Json\Request
 */
class GetNextStepRequest extends BaseRequest
{
    protected $number;

    public function __construct($number)
    {
        $this->number = $number;
    }

    public function getFullRequest()
    {
        return [
            'Envelope' => [
                'Header' => $this->getHeaders(),
                'Body' => $this->getBody()
            ]
        ];
    }

    protected function getBody()
    {
        return [
            "GetNextStepRequest" => [
                "version" => $this->getVersion(),
                "providerId" => "9118",
                "currentStep" => "PRE",
                "fieldsInfo" => [
                    "attribute" => [
                        [
                            'name' => 'CUSTOMFIELD:200',
                            'value' => 2,
                        ],
                        [
                            'name' => 'CUSTOMFIELD:106',
                            'value' => $this->number,
                        ]
                    ]
                ]
            ]
        ];
    }
}
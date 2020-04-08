<?php
/**
 * Created by PhpStorm.
 * User: won
 * Date: 2019-01-21
 * Time: 17:30
 */

namespace App\Packages\Providers;


use Illuminate\Contracts\Support\Arrayable;

class Result implements \JsonSerializable, Arrayable
{
    private $status = false;
    private $result = null;

    /**
     * @return bool
     */
    public function getStatusResult(): bool
    {
        return $this->status;
    }

    /**
     * @param bool $status
     * @return Result
     */
    public function setStatusResult(bool $status): Result
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return null
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @param null $result
     * @return Result
     */
    public function setResult($result)
    {
        $this->result = $result;
        return $this;
    }

    public function toArray() {
        return [
            'status' => $this->status,
            'result' => $this->result
        ];
    }


    /**
     * Specify data which should be serialized to JSON.
     *
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     *
     * @return mixed data which can be serialized by <b>json_encode</b>,
     *               which is a value of any type other than a resource.
     *
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }
}
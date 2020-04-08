<?php
/**
 * Created by PhpStorm.
 * User: won
 * Date: 2019-01-14
 * Time: 23:06
 */

namespace App\Packages\Loggers;


use Illuminate\Queue\SerializesModels;
use Log;

class CustomLogger
{
    use SerializesModels;

    protected $logger;
    protected $identity;

    public function __construct($directory = "")
    {
        $this->identity = (new Identity())->GenIdentity();
    }

    public function getIdentity()
    {
        return $this->identity;
    }

    public function setIdentity($identity)
    {
        $this->identity = $identity;
        return $this;
    }

    public function info($message, $data = [])
    {
        Log::info("[{$this->identity}]: {$message}", $this->convertObjectToArray($data));
    }

    public function error($message, $data = [])
    {
        Log::error("[{$this->identity}]: {$message}", $this->convertObjectToArray($data));
    }

    public function critical($message, $data = [])
    {
        Log::critical("[{$this->identity}]: {$message}", $this->convertObjectToArray($data));
    }

    public function warning($message, $data = [])
    {
        Log::warning("[{$this->identity}]: {$message}", $this->convertObjectToArray($data));
    }

    public function alert($message, $data = [])
    {
        Log::alert("[{$this->identity}]: {$message}", $this->convertObjectToArray($data));
    }

    public function emergency($message, $data = [])
    {
        Log::emergency("[{$this->identity}]: {$message}", $this->convertObjectToArray($data));
    }

    protected function convertObjectToArray($inputData)
    {
        if(is_object($inputData)) {
            return json_decode(json_encode($inputData), true);
        } else if(is_null($inputData)) {
            return [];
        }
        return $inputData;
    }
}
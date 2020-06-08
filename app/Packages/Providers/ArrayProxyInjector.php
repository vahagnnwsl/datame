<?php


namespace App\Packages\Providers;


use App\Proxy;

trait ArrayProxyInjector
{
    /**
     * Selected proxy model
     *
     * @var Proxy $proxy
     */
    private $proxy;

    /**
     * Select one random proxy model
     */
    public function selectProxy() {
        $this->proxy = Proxy::query()->inRandomOrder()->first();
    }

    /**
     * @return string|null
     */
    private function getIp() {
        return $this->proxy->ip ?? null;
    }

    /**
     * @return string|null
     */
    private function getPort() {
        return $this->proxy->port ?? null;
    }

    /**
     * @return string|null
     */
    private function getUserName() {
        return $this->proxy->username ?? null;
    }

    /**
     * @return string|null
     */
    private function getPassword() {
        return $this->proxy->password ?? null;
    }

    /**
     * Inject proxy options into array of options
     *
     * @param array $options
     * @return array
     */
    public function injectProxyOptions(array $options = []) {
        /*
         * TODO: Add proxy protocols here
         *  http
         *  https
         *  socks
         *  etc...
        */
        if (!$this->proxy) {
            return $options;
        }

        $proxyIpPort = $this->getIp() . (!$this->getPort()?: ":" . $this->getPort());
        $proxyUserPwd = $this->getUserName() . (!$this->getPassword()?: ":" . $this->getPassword());

        // Default http protocol
        $options['proxy'] = "http://" . (!$proxyUserPwd?: $proxyUserPwd . "@") . $proxyIpPort;

        $this->updateUsedCount();

        return $options;
    }

    private function updateUsedCount()
    {
        $this->proxy->used_count++;
        $this->proxy->save();
    }
}
<?php


namespace App\Packages\Providers;


use App\Proxy;

trait CurlProxyInjector
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
     * Inject curl proxy options into existing curl resource
     *
     * @param resource $ch
     * @return resource
     */
    public function injectProxyOptions($ch) {
        /*
         * TODO: Add proxy types support here if needed
         *  CURLPROXY_HTTP,
         *  CURLPROXY_HTTP_1_0,
         *  CURLPROXY_HTTPS,
         *  CURLPROXY_SOCKS4,
         *  CURLPROXY_SOCKS4A,
         *  CURLPROXY_SOCKS5,
         *  CURLPROXY_SOCKS5_HOSTNAME
        */
        if (!$this->proxy) {
            return $ch;
        }

        $proxyIpPort = $this->getIp() . (!$this->getPort()?: ":" . $this->getPort());
        $proxyUserPwd = $this->getUserName() . (!$this->getPassword()?: ":" . $this->getPassword());

        !$proxyIpPort?:curl_setopt($ch, CURLOPT_PROXY, $proxyIpPort);
        !$proxyUserPwd?:curl_setopt($ch, CURLOPT_PROXYUSERPWD, $proxyUserPwd);
        !$proxyIpPort && !$proxyUserPwd?:curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

        return $ch;
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: won
 * Date: 2019-02-23
 * Time: 00:10
 */

namespace App\Packages\Providers;


use App\App;
use App\Packages\Loggers\CustomLogger;
use DOMDocument;
use DOMXPath;
use PHPExcel_Exception;
use PHPExcel_IOFactory;
use PHPExcel_Reader_Exception;
use Throwable;
use GuzzleHttp\Client;
use simple_html_dom;

class FsinInformation
{
    /**
     * @var App
     */
    protected $app;
    protected $logger;
    protected $client;
    protected $url = '';

    public function __construct(App $app, CustomLogger $logger)
    {
        $this->app = $app;
        $this->logger = $logger;
        $this->client = new Client(
            [
                'base_uri' => 'http://xn--h1akkl.xn--p1ai',
            ]
        );
    }

    /**
     * @return Result
     * @throws PHPExcel_Exception
     * @throws PHPExcel_Reader_Exception
     */


    public function check()
    {
        $retData = new Result();

        $last_name = $this->app->lastname;
        $first_name = $this->app->name;
        $patronymic = $this->app->patronymic;

        $query = [
            'query' => [
                'arrFilterAdd_pf[territory2]' => '',
                'arrFilterAdd_pf[fio]' => $this->utf8_to_cp1251($last_name . ' ' . $first_name . ' ' . $patronymic),
                'set_filter' => 'Y'
            ]

        ];


        try {

            $resp = $this->client->get('/criminal/index.php', $query);
            $result = $resp->getBody()->getContents();

            $html = new simple_html_dom();
            $html->load($result);
            $divs = $html->find('div[class=sl-item]');
            if (count($divs) > 0) {
                $div = $divs[0];
                $fullname = $div->children[1]->children[1];
                $federalAuthorities = $div->children[1]->children[4]->children[1]->plaintext;
                $territorialAuthorities = $div->children[1]->children[3]->children[1]->plaintext;
                $result = $div->children[1]->children[2]->outertext;

                $retData->setResult([
                    'app_id' => $this->app->id,
                    'full_name' => trim($fullname->plaintext),
                    'federal_authorities' => trim($federalAuthorities),
                    'territorial_authorities' => trim($territorialAuthorities),
                    'result' => trim($result),
                ]);
                $retData->setStatusResult(true);
            } else {
                $retData->setResult([
                    'result' => 'Отсутствует',
                    'app_id' => $this->app->id
                ]);
            }


        } catch (\Exception $exception) {
            $this->logger->error("error : {$exception->getMessage()}, {$exception->getTraceAsString()}");
            $retData->setStatusResult(false);
            $retData->setResult([
                'error_message' => $exception->getMessage(),
                'app_id' => $this->app->id
            ]);

        }


        $this->logger->info("result", $retData->toArray());

        return $retData;

    }

    public function utf8_to_cp1251($s)
    {
        $byte2 = '';
        $out = '';
        $c1 = '';
        for ($c = 0; $c < strlen($s); $c++) {
            $i = ord($s[$c]);
            if ($i <= 127) $out .= $s[$c];
            if ($byte2) {
                $new_c2 = ($c1 & 3) * 64 + ($i & 63);
                $new_c1 = ($c1 >> 2) & 5;
                $new_i = $new_c1 * 256 + $new_c2;
                if ($new_i == 1025) {
                    $out_i = 168;
                } else {
                    if ($new_i == 1105) {
                        $out_i = 184;
                    } else {
                        $out_i = $new_i - 848;
                    }
                }
                $out .= chr($out_i);
                $byte2 = false;
            }
            if (($i >> 5) == 6) {
                $c1 = $i;
                $byte2 = true;
            }
        }
        return $out;
    }
}

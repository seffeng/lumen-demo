<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use GuzzleHttp\Client;
use Seffeng\LaravelHelpers\Helpers\Arr;
use Illuminate\Support\Facades\Log;

class Test extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:test {--debug} {--db=} {--count=1}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '测试command, 参数：
                        { --debug : is debug. }
                        { --db= : redis 使用数据库[0, 1,2, ...15]. }
                        { --count=1 : 循环次数. }
                        { example : php ./artisan command:test --debug --db=1}';

    /**
     *
     * @var string
     */
    protected $host = 'http://www.haier.com/2020920/';

    /**
     *
     * @var integer
     */
    protected $db;

    /**
     *
     * @var integer
     */
    protected $use;

    /**
     *
     * @var string
     */
    protected $proxy;

    /**
     *
     * @var string
     */
    protected $useragent;

    /**
     *
     * @var integer
     */
    protected $count = 0;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->db = $this->option('db');
        $count = $this->option('count', 1);

        try {
            for ($this->count; $this->count < $count; $this->count++) {
                $this->proxy = $this->getRandProxy();
                $this->useragent = $this->getRandUserAgent();
                $this->useGuzzle('/');
                Log::channel('single')->info('[' . date('Y-m-d H:i:s') . '][success] db: ' . $this->db . ', count: ' . $this->count . ', proxy: ' . json_encode($this->proxy) . ', UA: ' . $this->useragent);
                sleep(1);
            }
        } catch (\Exception $e) {
            Log::channel('single')->info('[' . date('Y-m-d H:i:s') . '][error] db: ' . $this->db . ', count: ' . $this->count . ', proxy: ' . json_encode($this->proxy) . ', UA: ' . $this->useragent . ', message: ' . $e->getMessage());
            $this->count++;
            $this->handle();
        }
    }

    /**
     *
     * @author zxf
     * @date    2020年9月19日
     * @param string $url
     * @return string
     */
    protected function useGuzzle(string $url)
    {
        $client = new Client(['base_uri' => $this->host, 'timeout' => 5]);
        $content = $client->get($url, [
            'proxy' => $this->proxy,
            'headers' => [
                'User-Agent' => $this->useragent,
                'Accept'     => 'application/json',
            ],
            'query' => [
                'all' => 'all'
            ]
        ])->getBody()->getContents();

        return $content;
    }

    /**
     * 添加代理IP配置
     * @author zxf
     * @date   2020年9月18日
     * @return array
     */
    private function fetchProxyItems()
    {
        return [
            ['http' => '222.188.204.177:15118'],
            ['http' => '119.101.32.56:23792'],
            ['http' => '58.53.43.118:18152'],
            ['http' => '223.241.78.103:21120'],
            ['http' => '114.99.220.178:23016'],
            ['http' => '123.160.225.81:22921'],
            ['http' => '220.161.101.87:22935'],
            ['http' => '42.179.168.87:15196'],
            ['http' => '59.35.47.193:22819'],
            ['http' => '182.98.12.158:21325'],
        ];
    }

    /**
     * 返回UA
     * @author zxf
     * @date   2020年9月18日
     * @return string
     */
    private function getRandUserAgent()
    {
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $spreadsheet = $reader->load(storage_path('ua_string.xlsx'));
        $rand = rand(1, intval($spreadsheet->getActiveSheet()->getHighestRow()));
        return $spreadsheet->getActiveSheet()->getCellByColumnAndRow(1, $rand)->getValue();
    }

    /**
     * 返回代理IP设置
     * @author zxf
     * @date   2020年9月18日
     * @return string
     */
    private function getRandProxy()
    {
        if (is_numeric($this->db)) {
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            $spreadsheet = $reader->load(storage_path('ip_string_' . $this->db . '.xlsx'));
            return ['http' => $spreadsheet->getActiveSheet()->getCellByColumnAndRow(1, $this->count + 1)->getValue()];
        } else {
            $proxyItems = $this->fetchProxyItems();
            $key = rand(0, count($proxyItems) - 1);
            return Arr::getValue($proxyItems, $key, []);
        }
    }
}

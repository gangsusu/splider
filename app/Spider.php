<?php


namespace App;


use GuzzleHttp\Client;
use Medoo\Medoo;
use Symfony\Component\DomCrawler\Crawler;

class Spider
{
    public function qiu_run()
    {
        $url = 'https://www.qiushibaike.com/article/123031068';
        $headers = [
            'user-agent' => 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/69.0.3497.100 Safari/537.36',
        ];
        $headers = ['user-agent' => (new agent())->random_uagent()];
        $client = new Client([
            'timeout' => 20,
            'headers' => $headers
        ]);
        $response = $client->request('GET', $url)->getBody()->getContents();
        $crawler = new Crawler();
        $crawler->addHtmlContent($response);

        $data = $crawler->filterXPath('//*[@id="single-next-link"]/div')->text();

        $database = new Medoo([
            'database_type' => 'mysql',
            'database_name' => 'qiushibaike',
            'server' => 'localhost',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8'
        ]);

        $database->insert('qiushibaike', [
            'content' => $data,
            'agent' => $headers['user-agent'],
            'create_time' => date('Y-m-d H:i:s')
        ]);
    }
    public function voa_run()
    {
        for ($i = 1; $i <= 72; $i++) {
            $url = 'https://www.51voa.com/Learn_A_Word_' . $i . '.html';
            $headers = [
                'user-agent' => 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/69.0.3497.100 Safari/537.36',
            ];
            $client = new Client([
                'timeout' => 200,
                'headers' => $headers
            ]);
            $response = $client->request('GET', $url)->getBody()->getContents();
            $crawler = new Crawler();
            $crawler->addHtmlContent($response);
            for ($j = 1; $j <= 50; $j++) {
                $data = $crawler->filterXPath('//*[@id="Right_Content"]/div[3]/ul/li[' . $j . ']/a')->text();
                $data = preg_replace('/\d+/', '', $data);
                $data = preg_replace('/:/', '', $data);
                $data = trim($data);
                $data = str_replace('-', ' ', $data);
                file_put_contents('./test.txt', $data . "\r\n", FILE_APPEND);
            }

        }


    }
}
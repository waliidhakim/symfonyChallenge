<?php
//
//namespace App\Services;
//
//use Goutte\Client;
//
//class ScrappingService
//{
//    private $client;
//
//    public function __construct()
//    {
//        $this->client = new Client();
//    }
//
//    public function scrapeProductDetails(string $url): array
//    {
//        $crawler = $this->client->request('GET', $url);
////        $name = $crawler->filter('meta[property="og:title"]')->attr('content');
////        $price = $crawler->filter('meta[property="product:price:amount"]')->attr('content');
////        $image = $crawler->filter('meta[property="og:image"]')->attr('content');
//
//        // Exemple d'extraction des informations. A adapter en fonction des sites cibles.
//        try {
//            $name = $crawler->filter("#productTitle")->text();
//            $price = $crawler->filter('.a-offscreen')->text();
//           $image = $crawler->filter('.imgTagWrapper img')->attr('src');
//        }catch (\Exception $exc) {
//           throw $exc;
//        }
//
//
//        return [
//           'name' => $name,
//            'price' => $price,
//          'image' => $image,
//        ];
//    }
//}


namespace App\Services;

use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\HttpClient\HttpClient;

class ScrappingService
{
    private $client;

    public function __construct()
    {
        // $this->client = new HttpBrowser(HttpClient::create());

        // $this->client = new HttpBrowser(HttpClient::create([
        //     'headers' => [
        //         'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.3',
        //     ],
        // ]));

        $userAgents = $this->getUserAgents();
        $randomUserAgent = $userAgents[array_rand($userAgents)];

        $this->client = new HttpBrowser(HttpClient::create([
            'headers' => [
                'User-Agent' => $randomUserAgent,
            ],
        ]));
    }

    private function getUserAgents(): array
    {
        // Retourner une liste d'User-Agents
        return [
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:99.0) Gecko/20100101 Firefox/99.0',
            'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/99.0.4844.51 Safari/537.36',
            'Mozilla/5.0 (iPad; CPU OS 13_2 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.3 Mobile/15E148 Safari/604.1',
            'Mozilla/5.0 (iPhone; CPU iPhone OS 13_2_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) FxiOS/32.0 Mobile/15E148 Safari/605.1.15',
            'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:99.0) Gecko/20100101 Firefox/99.0',
            'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/99.0.4844.82 Safari/537.36',
            'Mozilla/5.0 (Android 10; Mobile; rv:99.0) Gecko/99.0 Firefox/99.0',
            'Mozilla/5.0 (Linux; Android 10; SM-G975F) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/99.0.4844.58 Mobile Safari/537.36',
            'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_14_6) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/14.0 Safari/605.1.15',
            'Mozilla/5.0 (Windows NT 10.0; Trident/7.0; rv:11.0) like Gecko',
            'Mozilla/5.0 (Windows NT 6.1; WOW64; Trident/7.0; rv:11.0) like Gecko',
            'Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/99.0.4844.84 Safari/537.36',
            'Mozilla/5.0 (X11; CrOS x86_64 12871.102.0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/99.0.4844.94 Safari/537.36',
            'Mozilla/5.0 (X11; Linux i686) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/99.0.4844.51 Safari/537.36',
            'Mozilla/5.0 (Windows NT 6.1; rv:99.0) Gecko/20100101 Firefox/99.0',
            'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_5) AppleWebKit/601.7.7 (KHTML, like Gecko) Version/9.1.3 Safari/537.86.7',
            'Mozilla/5.0 (X11; Fedora; Linux x86_64; rv:99.0) Gecko/20100101 Firefox/99.0',
            'Mozilla/5.0 (Windows Phone 10.0; Android 6.0.1; Microsoft; Lumia 950) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/99.0.4844.58 Mobile Safari/537.36 Edge/40.15254.603',
            'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/99.0.4844.82 Safari/537.36 OPR/99.0.2192.71',
        ];
    }


    public function scrapeProductDetails(string $url): array
    {
        sleep(rand(2, 5));
        $crawler = $this->client->request('GET', $url);

        // Vérifiez si un CAPTCHA a été déclenché
        if ($crawler->filter('title')->text() == 'Robot Check') {
            // Gérer le CAPTCHA ou arrêter le processus ici
            throw new \Exception('CAPTCHA déclenché - arrêt du scraping pour cette URL.');
        }

        // dd($crawler);
        // Exemple d'extraction des informations. A adapter en fonction des sites cibles.
        try {
            // $name = $crawler->filter("#productTitle")->text();
            // $name = $crawler->filter('#productTitle')->text('', true);
            // $price = $crawler->filter('.a-offscreen')->text();
            // $image = $crawler->filter('.imgTagWrapper img')->attr('src');

            $name = $crawler->filter('.detailHeadline')->text('', true);
            $price = $crawler->filter('.price ')->text();
            $image = $crawler->filter('.prdMainPhoto img')->attr('src');
        } catch (\Exception $exc) {
            // dd($exc);
            throw $exc;
        }

        return [
            'name' => $name,
            'price' => $price,
            'image' => $image,
        ];
    }
}

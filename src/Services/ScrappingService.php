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

        $this->client = new HttpBrowser(HttpClient::create([
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.3',
            ],
        ]));
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

        //dd($crawler);
        // Exemple d'extraction des informations. A adapter en fonction des sites cibles.
        try {
            // $name = $crawler->filter("#productTitle")->text();
            $price = $crawler->filter('.a-offscreen')->text();
            $name = $crawler->filter('#productTitle')->text('', true);
            $image = $crawler->filter('.imgTagWrapper img')->attr('src');
        } catch (\Exception $exc) {
            dd($exc);
            throw $exc;
        }

        return [
            'name' => $name,
            'price' => $price,
            'image' => $image,
        ];
    }
}

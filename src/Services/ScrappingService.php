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
        $this->client = new HttpBrowser(HttpClient::create());
    }

    public function scrapeProductDetails(string $url): array
    {
        $crawler = $this->client->request('GET', $url);

        // Exemple d'extraction des informations. A adapter en fonction des sites cibles.
        try {
            $name = $crawler->filter(".product-title-word-break")->text();
            // $name = $crawler->filter("#productTitle")->text();
            $price = $crawler->filter('.a-offscreen')->text();
            $image = $crawler->filter('#imgTagWrapper img')->attr('src');
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

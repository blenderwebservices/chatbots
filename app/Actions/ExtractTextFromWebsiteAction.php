<?php

namespace App\Actions;

use Illuminate\Support\Facades\Http;
use Symfony\Component\DomCrawler\Crawler;

class ExtractTextFromWebsiteAction
{
    public function execute(string $websiteUrl)
    {
        $html = Http::get($websiteUrl)->body();

        $crawler = new Crawler($html);

        $arrayText = $crawler->filter('p')->each(function (Crawler $node) {
            return trim($node->text());
        });

        return implode(' ', $arrayText);
    }
}

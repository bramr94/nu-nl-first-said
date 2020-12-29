<?php

namespace App\Helpers;

use Goutte\Client;
use App\Models\Article;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Class Crawler
 *
 * @author Bram Raaijmakers
 *
 * @package App\Helpers
 */
class Crawler
{
    /**
     * @var Client
     */
    private $client;

    /**
     * Crawler constructor.
     */
    public function __construct()
    {
        $this->client = new Client();
    }

    /**
     * Retrieve the status code of the page.
     *
     * @param string $url
     *
     * @return integer
     */
    public function getStatusCode(string $url): int
    {
        $headers = @get_headers($url);
        if ($headers && isset($headers[14]) && strpos($headers[14], '200')) {
            return 200;
        }

        return 404;
    }

    /*
     * Check if the article was already crawled.
     *
     * @param int $id
     *
     * @return bool
     */
    public function alreadyCrawled(int $id): bool
    {
        if (
            DB::table('not_found_articles')->where('article_id', $id)->count() ||
            DB::table('articles')->where('article_id', $id)->count()
        ) {
            return true;
        }

        return false;
    }

    /**
     * Retrieve and store the article contents.
     *
     * @param string $url
     * @param int $id
     *
     * @return void
     */
    public function getArticle(string $url, int $id): void
    {
        $crawler = $this->client->request('GET', $url);
        $scripts = $crawler->filterXPath('//script[@type="application/ld+json"]');

        foreach ($scripts as $script) {
            if (str_contains($script->nodeValue, '"@type": "NewsArticle"') !== false) {
                $now = Carbon::now();
                $json = json_decode($script->nodeValue);

                DB::table('articles')->insert([
                    'article_id' => $id,
                    'url' => $json->mainEntityOfPage,
                    'title' => $json->headline,
                    'content' => $json->articleBody,
                    'created_at' => $now,
                    'updated_at' => $now
                ]);
            }
        }
    }
}

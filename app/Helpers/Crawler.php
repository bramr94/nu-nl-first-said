<?php

namespace App\Helpers;

use Goutte\Client;
use App\Models\Article;

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
        if ($headers && isset($headers[19]) && strpos($headers[19], '200')) {
            return 200;
        }

        return 404;
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
        // Last check if the ID already exists.
        if (Article::where('article_id', $id)->count()) {
            return;
        }

        $crawler = $this->client->request('GET', $url);
        $scripts = $crawler->filterXPath('//script[@type="application/ld+json"]');

        foreach ($scripts as $script) {
            if (str_contains($script->nodeValue, '"@type": "NewsArticle"') !== false) {
                $json = json_decode($script->nodeValue);

                Article::create([
                    'article_id' => $id,
                    'url' => $json->mainEntityOfPage,
                    'title' => $json->headline,
                    'content' => $json->articleBody
                ]);
            }
        }
    }
}

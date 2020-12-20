<?php

use Illuminate\Support\Facades\Route;

//Route::get('/', function () {
////    $html = file_get_contents('https://www.nu.nl/voetbal/6097731/psv-begint-met-herstelde-rosario-aan-uitwedstrijd-tegen-rkc.html?redirect=1');
////    $dom = new domDocument;
////    $dom->preserveWhiteSpace = false;
////    @$dom->loadHTML($html);
////    $scripts = $dom->getElementsByTagName('script');
////
////    foreach ($scripts as $script) {
////        if (str_contains($script->nodeValue, '"@type": "NewsArticle"') !== false) {
////            $json = json_decode($script->nodeValue);
////            $articleBody = str_replace('.', ' ', $json->articleBody);
////            $words = explode(' ', $articleBody);
////            dd($words);
////
////        }
////    }
////});

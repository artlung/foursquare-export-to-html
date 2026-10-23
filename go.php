<?php

use Artlung\FoursquareExportToHtml\Checkin;
use Artlung\FoursquareExportToHtml\Checkins;
use Artlung\FoursquareExportToHtml\Runner;

require __DIR__ . '/vendor/autoload.php';



$runner = new Runner();
$runner
    ->setFoursquareDumpPath(__DIR__ . '/../artlung.com/data/foursquare/artlung/data-export-255287/')
    ->setHtmlByDayPath(__DIR__ . '/../artlung.com/data/foursquare/artlung/html-checkins-by-day/')
    ->run();
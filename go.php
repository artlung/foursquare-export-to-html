<?php

require 'loader.php';

$checkin_double_check_filenames_and_venues = [];

$foursquare_dump_path = '../foursquare-data-export-255287/';
$output_path = '../foursquare-html-checkins-by-day/';

$files = scandir($foursquare_dump_path);
$files = array_filter($files, function($file) {
    return strpos($file, '.json') !== false;
});
// iterate over all files with root name 'checkins'
foreach ($files as $file) {
  if (strpos($file, 'checkins') === false) {
    continue;
  }
    $json = file_get_contents($foursquare_dump_path . $file);
    $json = json_decode($json, true);
    $checkins = new Checkins($json);

    print "Processing {$file}...\n";
    // print count summary
    print "Total checkins: {$checkins->count->getTotal()}\n";
    // iterate over all checkins
    /** @var Checkin $checkin */
    foreach ($checkins->getItems() as $checkin) {
        print $checkin->getLocalDateTime('Y-m-d H:i:s');
        print " ";
        print $checkin->getVenue();
        if ($venueUrl = $checkin->getVenueUrl()) {
            print " ({$venueUrl})";
        }
        print "\n";

        $eventNameAddendum = '';
        if ($checkin->event) {
            print "  Event: {$checkin->event->getName()}\n";
            $eventNameAddendum = " ({$checkin->event->getName()})";

        }


        $filename = $output_path . $checkin->getLocalDateTime('Y-m-d') . '.html';
        if (!isset($checkin_double_check_filenames_and_venues[$filename])) {
            $checkin_double_check_filenames_and_venues[$filename] = [];
        }
        $hasAlreadyBeenOutput = in_array($checkin->getVenue(), $checkin_double_check_filenames_and_venues[$filename]);
        $checkin_double_check_filenames_and_venues[$filename][] = $checkin->getVenue();
        if ($checkin->isNotDuplicate() && !$hasAlreadyBeenOutput && $checkin->getVenueUrl()) {
            $htmlChunk = <<<EOT
    <li title="{$checkin->getLocalDateTime('Y-m-d H:i:s')}">
       <a href="{$checkin->getVenueUrl()}" class="checkin-venue">{$checkin->getVenue()}</a>$eventNameAddendum
    </li>
EOT;
            // else has location
        } elseif ($checkin->location && $checkin->location->name) {
            $htmlChunk = <<<EOT
    <li title="{$checkin->getLocalDateTime('Y-m-d H:i:s')}" data-lat="{$checkin->location->lat}" data-lng="{$checkin->location->lng}">
       <span class="checkin-location">{$checkin->location->name}</span>
    </li>
EOT;

        } else {
            $htmlChunk = '';
        }
// if destination directory doesnt exist, create it
if (!file_exists($output_path)) {
    mkdir($output_path);
}
// if file does not exist create
if (!file_exists($filename)) {
    file_put_contents($filename, "<!--{$filename}-->" . "\n");
}

printf("To the file %s, appending:\n%s\n", $filename, $htmlChunk);
file_put_contents($filename, $htmlChunk, FILE_APPEND);


    }

}

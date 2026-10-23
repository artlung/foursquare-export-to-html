<?php

namespace Artlung\FoursquareExportToHtml;

use Exception;

class Runner
{


    private string $_foursquareDumpPath;
    private string $_htmlByDayPath;

    /**
     * Set the location for the Foursquare dumped path. If it does not exist or is wrong, throws exception
     *
     * @param string $string
     * @return $this
     * @throws Exception
     */
    public function setFoursquareDumpPath(string $string): static
    {
        if (!is_dir($string)) {
            throw new Exception("Path $string is not a directory");
        }
        $this->_foursquareDumpPath = $string;
        return $this;
    }

    /**
     * @throws Exception
     */
    public function setHtmlByDayPath(string $string): static
    {
        if (!is_dir($string)) {
            // try to make it
            if (!mkdir($string, 0777, true)) {
                throw new Exception("Path $string is not a directory and could not be created");
            }
        }
        $this->_htmlByDayPath = $string;
        return $this;
    }

    public function run(): void
    {

        $checkin_double_check_filenames_and_venues = [];

        // set these paths for import and export
        $foursquare_dump_path = $this->getFoursquareDumpPath();
        $output_path = $this->getHtmlByDayPath();

        $files = scandir($foursquare_dump_path);
        $files = array_filter($files, function ($file) {
            return str_contains($file, '.json');
        });
// iterate over all files with root name 'checkins'
        foreach ($files as $file) {
            if (!str_contains($file, 'checkins')) {
                continue;
            }
            $json = file_get_contents($foursquare_dump_path . $file);
            $json = json_decode($json, true);
            $checkins = new Checkins($json);

            print "Processing $file...\n";
            // print count summary
            print "Total checkins: {$checkins->count->getTotal()}\n";
            // iterate over all checkins
            /** @var Checkin $checkin */
            foreach ($checkins->getItems() as $checkin) {
                print $checkin->getLocalDateTime('Y-m-d H:i:s');
                print " ";
                print $checkin->getVenue();
                if ($venueUrl = $checkin->getVenueUrl()) {
                    print " ($venueUrl)";
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
// if destination directory doesn't exist, create it
                if (!file_exists($output_path)) {
                    mkdir($output_path);
                }
// if file does not exist create
                if (!file_exists($filename)) {
                    file_put_contents($filename, "<!--$filename-->" . "\n");
                }

                printf("To the file %s, appending:\n%s\n", $filename, $htmlChunk);
                file_put_contents($filename, $htmlChunk, FILE_APPEND);
            }
        }
    }

    private function getHtmlByDayPath(): string
    {
        return $this->_htmlByDayPath;
    }

    private function getFoursquareDumpPath(): string
    {
        return $this->_foursquareDumpPath;
    }

    public function removePriorHtmlByDayPath()
    {

        $output_path = $this->getHtmlByDayPath();
        if (is_dir($output_path)) {
            $files = scandir($output_path);
            foreach ($files as $file) {
                if (str_contains($file, '.html')) {
                    unlink($output_path . '/' . $file);
                }
            }
        }
        return $this;
    }
}
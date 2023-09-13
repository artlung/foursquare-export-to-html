<?php

class Checkin extends Base {
    // example json = {"id":"64fe70f21294485a9dd37456","createdAt":"2023-09-11 01:44:18.000000","type":"checkin","private":true,"visibility":"private","timeZoneOffset":-420,"venue":{"id":"4aabbd5cf964a520e55920e3","name":"Ocean Beach Dog Beach","url":"https:\/\/foursquare.com\/v\/ocean-beach-dog-beach\/4aabbd5cf964a520e55920e3"},"comments":{"count":0}}
    public string $id;
    public string $createdAt;
    public string $type;
    public ?bool $private;
    public ?string $visibility;
    public int $timeZoneOffset;
    public ?Venue $venue = null;
    public ?Event $event = null;
    public ?Location $location = null;

    public function __construct($json) {
        $this->setJson($json);
        $this->id = $json['id'];
        $this->createdAt = $json['createdAt'];
        $this->type = $json['type'];
        if (isset($json['private'])) {
            $this->private = $json['private'];
        }
        if (isset($json['visibility'])) {
            $this->visibility = $json['visibility'];
        }
        $this->timeZoneOffset = $json['timeZoneOffset'];
        if (isset($json['venue'])) {
            $this->venue = new Venue($json['venue']);
        }
        // if type is venueless, create fake venue
        if ($this->type == 'venueless' && array_key_exists('location', $json)) {
            $this->location = new Location($json['location']);
        }
        if (isset($json['event'])) {
            $this->event = new Event($json['event']);
        }
    }

    public function getVenue()
    {
        if ($this->venue && $this->venue->name) {
            return $this->venue->name;
        } else if ($this->location && $this->location->name) {
            return $this->location->name;
        } else {
            return "No venue found for this checkin. Might be a duplicate";
        }
    }

    /**
     * @return bool
     */
    public function isNotDuplicate(): bool
    {
        return $this->venue && $this->venue->name;
    }

    /**
     * @param string $dateTimeFormat
     * @return string
     */
    public function getLocalDateTime(string $dateTimeFormat)
    {
//        $offset = $this->timeZoneOffset;
//        return $dateTime->format($dateTimeFormat);

        $date = new DateTime($this->createdAt);

//        $date = DateTime::createFromFormat('U', $this->createdAt);

        # Convert timeZoneOffset (minutes) to format accepted by DateTimeZone (e.g. "+HHMM" or "-HHMM")
        $offset = sprintf('%+02d%02d', $this->timeZoneOffset / 60, abs($this->timeZoneOffset % 60));
        $date->setTimeZone(new DateTimeZone($offset));

        return $date->format($dateTimeFormat);
    }

    public function getVenueUrl()
    {
        if ($this->venue) {
            return $this->venue->getUrl();
        }
        return '';
    }

}
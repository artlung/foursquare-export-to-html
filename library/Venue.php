<?php

class Venue extends Base {
    // example json {"id":"4aabbd5cf964a520e55920e3","name":"Ocean Beach Dog Beach","url":"https:\/\/foursquare.com\/v\/ocean-beach-dog-beach\/4aabbd5cf964a520e55920e3"}
    public string $id;
    public string $name;
    public string $url;
    // constructor
    public function __construct($json) {
        $this->setJson($json);
        $this->id = $json['id'];
        $this->name = $json['name'];
        $this->url = $json['url'];
    }

    // get url
    public function getUrl() {
        return $this->url;
    }
}
<?php

class Location extends Base {
    // json {"name":"mill mountain coffee and tea","lat":37.24381973819321,"lng":-79.99104109142723}
    public string $name;
    public float $lat;
    public float $lng;

    // constructor
    public function __construct($json) {
        $this->setJson($json);
        $this->name = $json['name'];
        $this->lat = $json['lat'];
        $this->lng = $json['lng'];
    }
}
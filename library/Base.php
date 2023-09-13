<?php

class Base {
    protected $json;

    public function __construct($json = '') {
        if ($json) {
            $this->setJson($json);
        }
    }
    public function setJson($json) {
        $this->json = $json;
    }

    public function __toString() {
        return json_encode($this->json);
    }
}
<?php

namespace Artlung\FoursquareExportToHtml;
class Base {
    protected $json;

    public function setJson($json) {
        $this->json = $json;
    }

    public function __toString() {
        return json_encode($this->json);
    }
}
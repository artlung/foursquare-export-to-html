<?php

class Items extends Base {


    // constructor
    private array $checkins = [];

    public function __construct($json) {
        $this->setJson($json);
        foreach ($json as $item) {
            $this->checkins[] = new Checkin($item);
        }
    }

    /**
     * @return Checkin[]
     */
    public function getCheckins()
    {
        return $this->checkins;
    }
}
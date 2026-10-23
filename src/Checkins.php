<?php

class Checkins extends Base {

    public Items $items;
    public Count $count;

    public function __construct($json) {
        $this->setJson($json);
        $this->items = new Items($json['items']);
        $this->count = new Count($json['count']);

    }

    /**
     * @return array
     */
    public function getItems()
    {
        return $this->items->getCheckins();
    }
}
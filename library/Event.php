<?php

class Event extends Base
{

    // example json {"id":"5becf4db0868a2002c142e7b","name":"Ralph Breaks the Internet","categories":[{"id":"4dfb90c6bd413dd705e8f897","name":"Movie","pluralName":"Movies","shortName":"Movie","icon":{"prefix":"https:\/\/ss3.4sqi.net\/img\/categories_v2\/arts_entertainment\/movietheater_","suffix":".png"},"primary":true}]}
    public ?string $id;
    public string $name;
    public ?Categories $categories;

    /**
     * @param mixed $event
     */
    public function __construct($event)
    {
        $this->setJson($event);
        if (isset($event['id'])) {
            $this->id = $event['id'];
        }
        if (isset($event['name'])) {
            $this->name = $event['name'];
        }
        if (isset($event['categories'])) {
            $this->categories = new Categories($event['categories']);
        }
    }

    public function getName()
    {
        $name = '';
        if ($this->name) {
            $name = $this->name;
        }
        $categories = [];
        if (isset($this->categories) && isset($this->categories->categories[0])) {
            $categories[] = $this->categories->categories[0]->name;
        }
        if (count($categories) > 0) {
            return $name . ' (' . implode(', ', $categories) . ')';
        } else {
            return $name;
        }


    }
}
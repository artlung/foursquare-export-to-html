<?php

class Category extends Base
{

    // example json {"id":"4dfb90c6bd413dd705e8f897","name":"Movie","pluralName":"Movies","shortName":"Movie","icon":{"prefix":"https:\/\/ss3.4sqi.net\/img\/categories_v2\/arts_entertainment\/movietheater_","suffix":".png"},"primary":true}
    public string $id;
    public string $name;
    public string $pluralName;
    public string $shortName;
    public ?Icon $icon;

    /**
     * @param mixed $item
     */
    public function __construct($item)
    {
        $this->setJson($item);
        if (!$item) {
            return;
        }
        $this->id = $item['id'];
        $this->name = $item['name'];
        $this->pluralName = $item['pluralName'];
        $this->shortName = $item['shortName'];
        if (isset($item['icon'])) {
            $this->icon = new Icon($item['icon']);
        }

    }
}
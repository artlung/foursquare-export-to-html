<?php

class Categories extends Base
{
    // set a category for each item in the json
    //[{"id":"4dfb90c6bd413dd705e8f897","name":"Movie","pluralName":"Movies","shortName":"Movie","icon":{"prefix":"https:\/\/ss3.4sqi.net\/img\/categories_v2\/arts_entertainment\/movietheater_","suffix":".png"},"primary":true}]
    public array $categories;

    public function __construct($json)
    {
        $this->setJson($json);
        foreach ($json as $item) {
            $this->categories[] = new Category($item);
        }
    }
}
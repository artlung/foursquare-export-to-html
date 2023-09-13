<?php

class Icon extends Base
{
    // example json {"prefix":"https:\/\/ss3.4sqi.net\/img\/categories_v2\/arts_entertainment\/movietheater_","suffix":".png"}
    public string $prefix;
    public string $suffix;

    /**
     * @param mixed $item
     */
    public function __construct($item)
    {
        $this->setJson($item);
        if (!$item) {
            return;
        }
        $this->prefix = $item['prefix'];
        $this->suffix = $item['suffix'];


    }

    public function getIconUrl(): string
    {
        return $this->prefix . 'bg_64' . $this->suffix;
    }

}
<?php

require __DIR__ . '/vendor/autoload.php';
use Imgix\UrlBuilder;

class Plugin_imgix extends Plugin
{
    protected $builder;

    function __construct() {
        parent::__construct();

        $builder = new UrlBuilder($this->fetchConfig('source'));
        $builder->setUseHttps($this->fetchConfig('use_https', true));

        if ($secureURLToken = $this->fetchConfig('secure_url_token')) {
            $builder->setSignKey($secureURLToken);
        }

        $this->builder = $builder;
    }

    public function url($path, $params=array())
    {
        return "$path, $params"
    }
}

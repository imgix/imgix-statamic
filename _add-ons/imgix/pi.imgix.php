<?php

require __DIR__ . '/vendor/autoload.php';
use Imgix\UrlBuilder;

class Plugin_imgix extends Plugin
{
    protected $builder;

    protected function build_url($params) {
        $path = array_shift($params);

        return $this->builder->createURL($path, $params);
    }

    protected function build_srcset($params) {
        $srcset_values = array();
        $resolutions = $this->fetchConfig('responsive_resolutions', array(1, 2));

        foreach ($resolutions as $resolution) {
            $srcset_params = $params;

            if ($resolution != 1) {
                $srcset_params['dpr'] = $resolution;
            }

            $srcset_value = $this->build_url($srcset_params) . ' ' . $resolution . 'x';

            array_push($srcset_values, $srcset_value);
        }

        return join(',', $srcset_values);
    }

    function __construct() {
        parent::__construct();

        $builder = new UrlBuilder($this->fetchConfig('source'));
        $builder->setUseHttps($this->fetchConfig('use_https', true));

        if ($secureURLToken = $this->fetchConfig('secure_url_token')) {
            $builder->setSignKey($secureURLToken);
        }

        $this->builder = $builder;
    }

    public function image_url()
    {
        return $this->build_url($this->attributes);
    }

    public function image_tag()
    {
        return '<img src="' . $this->build_url($this->attributes) . '">';
    }

    public function responsive_image_tag()
    {
        $params = $this->attributes;

        return join('', array(
            '<img srcset="',
                $this->build_srcset($params),
            '" src="',
                $this->build_url($params),
            '">'
        ));
    }

    public function picture_tag()
    {
        $params = $this->attributes;

        return join('', array(
            '<picture>',
                '<source srcset="',
                    $this->build_srcset($params),
                '">',
                '<img src="',
                    $this->build_url($params),
                '">',
            '</picture>'
        ));
    }
}

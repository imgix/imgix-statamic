<?php

namespace Statamic\Addons\Imgix;

use Statamic\Extend\Tags;
use Imgix\UrlBuilder;

class ImgixTags extends Tags
{
    protected $builder;

    protected function buildUrl($params) {
        $path = array_shift($params);

        return $this->builder->createURL($path, $params);
    }

    protected function buildSrcset($params) {
        $srcset_values = array();
        $resolutions = $this->getConfig('responsive_resolutions', array(1, 2));

        foreach ($resolutions as $resolution) {
            $srcset_params = $params;

            if ($resolution != 1) {
                $srcset_params['dpr'] = $resolution;
            }

            $srcset_value = $this->buildUrl($srcset_params) . ' ' . $resolution . 'x';

            array_push($srcset_values, $srcset_value);
        }

        return join(',', $srcset_values);
    }

    function init() {
        $builder = new UrlBuilder($this->getConfig('source'));
        $builder->setUseHttps($this->getConfig('use_https', true));

        if ($secureURLToken = $this->getConfig('secure_url_token')) {
            $builder->setSignKey($secureURLToken);
        }

        $this->builder = $builder;
    }

    public function index() {
        return $this->buildUrl($this->parameters);
    }

    public function imageUrl()
    {
        return $this->buildUrl($this->parameters);
    }

    public function imageTag()
    {
        return '<img src="' . $this->buildUrl($this->parameters) . '">';
    }

    public function responsiveImageTag()
    {
        $params = $this->parameters;

        return join('', array(
            '<img srcset="',
                $this->buildSrcset($params),
            '" src="',
                $this->buildUrl($params),
            '">'
        ));
    }

    public function pictureTag()
    {
        $params = $this->parameters;

        return join('', array(
            '<picture>',
                '<source srcset="',
                    $this->buildSrcset($params),
                '">',
                '<img src="',
                    $this->buildUrl($params),
                '">',
            '</picture>'
        ));
    }
}

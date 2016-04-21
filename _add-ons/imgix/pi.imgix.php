<?php

require __DIR__ . '/vendor/autoload.php';
use Imgix\UrlBuilder;

class Plugin_imgix extends Plugin
{
    protected $builder;

    protected function categorized_attributes() {
        $attrs = $this->attributes;

        $categorized_attrs = array(
            'path' => $attrs['path'],
            'img_attributes' => array(),
            'imgix_attributes' => array()
        );

        unset($attrs['path']);

        while (list($key, $val) = each($attrs)) {
            $is_img_attr = in_array($key, array('alt', 'longdesc', 'title'));
            $is_data_attr = strpos($key, 'data-') === 0;
            $is_aria_attr = strpos($key, 'aria-') === 0;

            if ($is_img_attr || $is_data_attr || $is_aria_attr) {
                $categorized_attrs['img_attributes'][$key] = $val;
            } else {
                $categorized_attrs['imgix_attributes'][$key] = $val;
            }
        }

        return $categorized_attrs;
    }

    protected function build_url($categorized_attrs) {
        return $this->builder->createURL(
            $categorized_attrs['path'],
            $categorized_attrs['imgix_attributes']
        );
    }

    protected function build_img_attributes($categorized_attrs) {
        $img_attributes = $categorized_attrs['img_attributes'];

        $html = '';

        while (list($key, $val) = each($img_attributes)) {
            $html .= " $key=\"$val\"";
        }

        return $html;
    }

    protected function build_srcset($categorized_attrs) {
        $srcset_values = array();
        $resolutions = $this->fetchConfig('responsive_resolutions', array(1, 2));

        foreach ($resolutions as $resolution) {
            if ($resolution != 1) {
                $categorized_attrs['imgix_attributes']['dpr'] = $resolution;
            }

            $srcset_value = $this->build_url($categorized_attrs) . ' ' . $resolution . 'x';

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

    public function image_url() {
        return $this->build_url($this->categorized_attributes());
    }

    public function image_tag() {
        $categorized_attrs = $this->categorized_attributes();

        return join('', array(
            '<img src="',
                $this->build_url($categorized_attrs),
            '" ',
                $this->build_img_attributes($categorized_attrs),
            '>'
        ));
    }

    public function responsive_image_tag() {
        $categorized_attrs = $this->categorized_attributes();

        return join('', array(
            '<img srcset="',
                $this->build_srcset($categorized_attrs),
            '" src="',
                $this->build_url($categorized_attrs),
            '" ',
                $this->build_img_attributes($categorized_attrs),
            '>'
        ));
    }

    public function picture_tag() {
        $categorized_attrs = $this->categorized_attributes();

        return join('', array(
            '<picture>',
                '<source srcset="',
                    $this->build_srcset($categorized_attrs),
                '">',
                '<img src="',
                    $this->build_url($categorized_attrs),
                '" ',
                    $this->build_img_attributes($categorized_attrs),
                '>',
            '</picture>'
        ));
    }
}

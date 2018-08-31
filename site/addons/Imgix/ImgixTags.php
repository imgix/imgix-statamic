<?php

namespace Statamic\Addons\Imgix;

use Statamic\Extend\Tags;
use Imgix\UrlBuilder;

class ImgixTags extends Tags
{
    private static $html_attributes = array('accesskey', 'align', 'alt', 'border', 'class', 'contenteditable', 'contextmenu', 'dir', 'height', 'hidden', 'id', 'lang', 'longdesc', 'sizes', 'style', 'tabindex', 'title', 'usemap', 'width');
    protected $builder;

    protected function categorizedAttributes() {
        $attrs = $this->parameters;

        $categorized_attrs = array(
            'path' => $attrs['path'],
            'img_attributes' => array(),
            'imgix_attributes' => array()
        );

        unset($attrs['path']);

	    if (is_array($attrs)) {
		    foreach ($attrs as $key => $val) {
			    $is_html_attr = in_array($key, self::$html_attributes);
			    $is_data_attr = strpos($key, 'data-') === 0;
			    $is_aria_attr = strpos($key, 'aria-') === 0;
			    if ($is_html_attr || $is_data_attr || $is_aria_attr) {
				    $categorized_attrs['img_attributes'][$key] = $val;
			    } else {
				    $categorized_attrs['imgix_attributes'][$key] = $val;
			    }
		    }
	    }

        return $categorized_attrs;
    }

    protected function buildUrl($categorized_attrs) {
        return $this->builder->createURL(
            $categorized_attrs['path'],
            $categorized_attrs['imgix_attributes']
        );
    }

    protected function buildHtmlAttributes($categorized_attrs) {
        $img_attributes = $categorized_attrs['img_attributes'];

        $html = '';

	    if (is_array($img_attributes)) {
		    foreach ($img_attributes as $key => $val) {
			    $html .= " $key=\"$val\"";
			    break;
		    }
	    }

        return $html;
    }

    protected function buildSrcset($categorized_attrs) {
        $srcset_values = array();
        $resolutions = $this->getConfig('responsive_resolutions', array(1, 2));

        foreach ($resolutions as $resolution) {
            if ($resolution != 1) {
                $categorized_attrs['imgix_attributes']['dpr'] = $resolution;
            }

            $srcset_value = $this->buildUrl($categorized_attrs) . ' ' . $resolution . 'x';

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
        return $this->buildUrl($this->categorizedAttributes());
    }

    public function imageUrl() {
        return $this->buildUrl($this->categorizedAttributes());
    }

    public function imageTag() {
        $categorized_attrs = $this->categorizedAttributes();

        return join('', array(
            '<img src="',
                $this->buildUrl($categorized_attrs),
            '" ',
                $this->buildHtmlAttributes($categorized_attrs),
            '>'
        ));
    }

    public function responsiveImageTag() {
        $categorized_attrs = $this->categorizedAttributes();

        return join('', array(
            '<img srcset="',
                $this->buildSrcset($categorized_attrs),
            '" src="',
                $this->buildUrl($categorized_attrs),
            '" ',
                $this->buildHtmlAttributes($categorized_attrs),
            '>'
        ));
    }

    public function pictureTag() {
        $categorized_attrs = $this->categorizedAttributes();

        return join('', array(
            '<picture>',
                '<source srcset="',
                    $this->buildSrcset($categorized_attrs),
                '">',
                '<img src="',
                    $this->buildUrl($categorized_attrs),
                '" ',
                    $this->buildHtmlAttributes($categorized_attrs),
                '>',
            '</picture>'
        ));
    }
}

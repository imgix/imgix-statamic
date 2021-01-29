<!-- ix-docs-ignore -->
![imgix logo](https://assets.imgix.net/sdk-imgix-logo.svg)

`imgix-statamic` is an add-on for integrating [imgix](https://www.imgix.com/) into Statamic sites.

[![Statamic Version](https://img.shields.io/badge/statamic-2.1-blue.svg )](https://statamic.com/marketplace/addons/imgix-statamic)
[![Build Status](https://travis-ci.org/imgix/imgix-statamic.svg?branch=main)](https://travis-ci.org/imgix/imgix-statamic)
[![License](https://img.shields.io/github/license/imgix/imgix-statamic)](https://github.com/imgix/imgix-statamic/blob/main/LICENSE.md)
[![FOSSA Status](https://app.fossa.com/api/projects/git%2Bgithub.com%2Fimgix%2Fimgix-statamic.svg?type=shield)](https://app.fossa.com/projects/git%2Bgithub.com%2Fimgix%2Fimgix-statamic?ref=badge_shield)

---
<!-- /ix-docs-ignore -->

- [Installation](#installation)
- [Usage](#usage)
    * [`imgix:image_tag`](#imgiximage_tag)
    * [`imgix:image_url`](#imgiximage_url)
    * [`imgix:responsive_image_tag`](#imgixresponsive_image_tag)
    * [`imgix:picture_tag`](#imgixpicture_tag)
        + [Pass-through Attributes](#pass-through-attributes)
- [Meta](#meta)
- [License](#license)

**Please note that the base branch of `imgix-statamic` is designed for Statamic `v2.x` and that we are no longer actively developing for Statamic `v1.x`. If you are using Statmic `v1.x`, take a peek at [the version 1 branch](https://github.com/imgix/imgix-statamic/tree/v1)**.

## Installation

1. Copy `site/addons/imgix` into your Statamic installation's `site/addons` folder. Source can be downloaded from the [Statamic Marketplace](https://statamic.com/marketplace/addons/imgix-statamic).
2. Copy `site/settings/addons/imgix.yaml` into your Statamic installation's `site/settings/addons` folder.
3. Configure `imgix.yaml`:
    * `source` (Required): The imgix source domain to grab images from.
    * `secure_url_token`: Add this if you want to output signed (secure) image URLs.
    * `use_https`: Whether to use HTTPS URLs.
    * `responsive_resolutions`: The device pixel ratios used to generate `srcset` attributes.
4. Run `please addons:refresh` to install dependencies.

## Usage

After you've installed and configured imgix-statamic, you can start using it in your Statamic templates! You can use [any available imgix parameter](https://www.imgix.com/docs/reference), including face detection, automatic format detection (e.g. WebP), and more. Here are some examples to get you started:

### `imgix:image_tag`

`imgix:image_tag` allows users to generate `<img>` elements quickly with the option of appending imgix parameters to the image source.

``` html
{{ imgix:image_tag path="dogs.png" w="200" rot="10" alt="Some dogs!" }}
```

Will output the following HTML:

``` html
<img src="https://your-source.imgix.net/dogs.png?w=200&rot=10" alt="Some dogs!">
```

### `imgix:image_url`

`imgix:image_url` allows users to construct image URLs quickly with the option of appending imgix parameters.

``` html
{{ imgix:image_url path="dogs.png" w="200" }}
```

Will output the following URL:

``` html
https://your-source.imgix.net/dogs.png?w=200
```

### `imgix:responsive_image_tag`

`imgix:responsive_image_tag` will generate an `img` element with a filled-out `srcset` attribute that leans on imgix to do the hard work. It uses the configured device pixel ratios in the `responsive_resolutions` config variable (which defaults to `[1, 2]`). We talk a bit about using the `srcset` attribute in an application in the following blog post: [“Responsive Images with srcset and imgix”](http://blog.imgix.com/post/127012184664/responsive-images-with-srcset-imgix).

``` html
{{ imgix:responsive_image_tag path="dogs.png" w="200" }}
```

Will output the following HTML:

``` html
<img srcset="https://your-source.imgix.net/dogs.png?w=200 1x,
             https://your-source.imgix.net/dogs.png?w=200&dpr=2 2x"
     src="https://your-source.imgix.net/dogs.png?w=200">
```

### `imgix:picture_tag`

`imgix:picture_tag` will generate a `<picture>` element with a single `source` element and a fallback `img` element. It uses the configured device pixel ratios in the `responsive_resolutions` config variable (which defaults to `[1, 2]`).

``` html
{{ imgix:picture_tag path="dogs.png" w="200" alt="Some dogs!" }}
```

Will output the following HTML:

``` html
<picture>
    <source srcset="https://your-source.imgix.net/dogs.png?w=200 1x,
                    https://your-source.imgix.net/dogs.png?w=200&dpr=2 2x">
    <img src="https://your-source.imgix.net/dogs.png?w=200" alt="Some dogs!">
</picture>
```

#### Pass-through Attributes

Any imgix method that generates an `img` tag (`image_tag`, `responsive_image_tag`, and `picture_tag`) will automatically pass through the following attributes to the tag, if provided:

* `alt`
* `longdesc`
* `title`
* `data-*`
* `aria-*`

## Meta

imgix-statamic was made by [imgix](http://imgix.com). It's licensed under the BSD 2-Clause license (see the [license file](https://github.com/imgix/imgix-statamic/blob/main/license.md) for more info).

## License
[![FOSSA Status](https://app.fossa.com/api/projects/git%2Bgithub.com%2Fimgix%2Fimgix-statamic.svg?type=large)](https://app.fossa.com/projects/git%2Bgithub.com%2Fimgix%2Fimgix-statamic?ref=badge_large)

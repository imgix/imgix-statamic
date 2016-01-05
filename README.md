![imgix logo](https://assets.imgix.net/imgix-logo-web-2014.pdf?page=2&fm=png&w=200&h=200)

# imgix-statamic [![Build Status](https://travis-ci.org/imgix/imgix-statamic.svg?branch=master)](https://travis-ci.org/imgix/imgix-statamic)

Easily generate imgix URLs inside Statamic.

* [Installation](#installation)
* [Usage](#usage)
* [Meta](#meta)

<a name="installation"></a>
## Installation

1. Copy the `_add-ons` folder contents to your Statamic root directory.
2. Do the same with the files in the `_config` directory.
3. Configure `imgix.yaml` with your custom values:
    * `source` (Required): The imgix source domain to grab images from.
    * `secure_url_token`: Add this if you want to output signed (secure) image URLs.
    * `use_https`: Whether to use HTTPS URLs.


<a name="usage"></a>
## Usage

After you've installed and configured imgix-statamic, you can start using it in your Statamic templates! Here's an example to get you started:

``` html
<img src="{{ imgix:url path="dog.png" w="300" h="200" fit="crop" }}">
```

That's a very basic use case, and you can use [any available imgix parameter](https://www.imgix.com/docs/reference), including face detection, automatic format detection (WebP!), and more.


<a name="meta"></a>
## Meta

imgix-statamic was made by [imgix](http://imgix.com). It's licensed under the BSD 2-Clause license (see the [license file](https://github.com/imgix/imgix-statamic/blob/master/license.md) for more info).

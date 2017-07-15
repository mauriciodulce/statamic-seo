# A full featured SEO Suite for Statamic

[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![SensioLabsInsight](https://img.shields.io/sensiolabs/i/c749b485-2d14-4a28-a50f-06499e83390e.svg?style=flat-square)](https://insight.sensiolabs.com/projects/c749b485-2d14-4a28-a50f-06499e83390e)
[![Quality Score](https://img.shields.io/scrutinizer/g/marbles/statamic-seo.svg?style=flat-square)](https://scrutinizer-ci.com/g/marbles/statamic-seo)
[![StyleCI](https://styleci.io/repos/83453964/shield?branch=master)](https://styleci.io/repos/83453964)

![Screenshot](http://i.imgur.com/ALbubDX.png)

## Installation

Download or clone the repository, rename the folder to `Seo` then copy the folder to your site's `Addons` directory, then refresh the add-ons to install the dependencies.

``` bash
php please addons:refresh
```
## Features

- Global SEO Meta tags, including Facebook, Twitter and Google+ specific tags
- Website ownership tags for Google & Bing
- Google Analytics code
- Google Tag Manager code
- JSON-LD schema for Semantic Web (used by Google Knowledge graph)

__This add-on does not create a humans.txt or robots.txt, this is something easily done manually__

## Usage

**This Add-on assumes you have a container `images` for the uploads of the seo images.**

### Tag

All you need to do to use the add-on is fill out the settings and add the `{{ seo }}` tag to the `head` in your main layout file.  

### Overwriting meta

You can overwrite the default meta by having content with certain fields, the SEO Add-on checks in the following order:

- `seo_title` => `title` => `global title`
- `seo_description` => `description` => `global description`
- `seo_image` => `global image`

The global values are retrieved from the SEO Add-on settings

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email rias@marbles.be instead of using the issue tracker.

## Credits

- [Rias Van der Veken](https://github.com/rias500)
- [All Contributors](../../contributors)

- Special thanks to the CraftCMS plug-in [SEOmatic](https://github.com/nystudio107/seomatic) for the many examples.

## About Marbles
Marbles is a digital communication agency based in Antwerp, Belgium. Learn more about us [on our website](https://www.marbles.be).

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

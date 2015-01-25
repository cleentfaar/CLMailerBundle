# MailerBundle [![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](https://github.com/cleentfaar/CLMailerBundle/tree/master/LICENSE.md)

Bundle providing abstraction for mailer services and templates.
One of it's key features is that it let's you support both HTML and plain text formatting in one template.

[![Build Status](https://img.shields.io/travis/cleentfaar/CLMailerBundle/master.svg?style=flat-square)](https://travis-ci.org/cleentfaar/CLMailerBundle)
[![Coverage Status](https://img.shields.io/scrutinizer/coverage/g/cleentfaar/CLMailerBundle.svg?style=flat-square)](https://scrutinizer-ci.com/g/cleentfaar/CLMailerBundle/code-structure)
[![Quality Score](https://img.shields.io/scrutinizer/g/cleentfaar/CLMailerBundle.svg?style=flat-square)](https://scrutinizer-ci.com/g/cleentfaar/CLMailerBundle)
[![Latest Version](https://img.shields.io/github/release/cleentfaar/CLMailerBundle.svg?style=flat-square)](https://github.com/cleentfaar/CLMailerBundle/releases)
[![Total Downloads](https://img.shields.io/packagist/dt/cleentfaar/mailer-bundle.svg?style=flat-square)](https://packagist.org/packages/cleentfaar/mailer-bundle)


## Features

- Supports Twig and PHP templates
- Automatically handles the different formatting of HTML and plain-text e-mails for you
- Use your own stylesheet and have it injected into the e-mail's HTML as inline `style`-attributes automatically (maximum compatibility with e-mail readers like Google's - see [aim of this bundle](#aim-of-this-bundle) below)!
- Base HTML layout already has a very decent set-up for you to use (but you can always go your own way of course!)
- Create mailers by simply tagging them with `cl_mailer.mailer`. Keep your constructor/dependencies to yourself!
- Install the KnpMarkdownBundle and use the power of markdown templates; one template for both HTML and plain-text (see [Usage](https://github.com/cleentfaar/CLMailerBundle/blob/master/Resources/doc/usage.md))!
- Easily translatable subjects for your e-mails (just define them under the `mailer` domain)
- Re-use default configuration: define it once and forget!


## Documentation

- [Installation](https://github.com/cleentfaar/CLMailerBundle/blob/master/Resources/doc/installation.md)
- [Configuration](https://github.com/cleentfaar/CLMailerBundle/blob/master/Resources/doc/configuration.md)
- [Usage](https://github.com/cleentfaar/CLMailerBundle/blob/master/Resources/doc/usage.md)
- [Contributing](https://github.com/cleentfaar/CLMailerBundle/blob/master/Resources/doc/contributing.md)


## Aim of this bundle

### Remove any repetitive work
The aim of the this bundle is to abstract away the work of rendering and sending e-mails, and make sure that as much e-mail
clients are supported as possible (for HTML e-mails).

### Re-use values across different email types
To achieve this a set of e-mail types can be defined by you in configuration, along with some helpful defaults.
Then, whenever you want to send an e-mail of one of these pre-defined types, you only have to refer to it by it's name.

The mailer can then re-use the pre-defined values, along with any values provided by you during the actual sending of the e-mail.

Besides having these pre-set defaults, I have also done my best to provide a very compatible HTML layout to extend from
(see the `{% extend layout %}` example below).

### Write CSS, but support all e-mail clients (converted to inline `style`-attributes)
Additionally, I have added a way for you to provide a stylesheet that will be included in the rendered e-mail.
What's special about it is that the stylesheet won't simply be included within `<style>`-tags (because that would not be
compatible with EVERY mail-client's rendering rules, like Gmail).

Instead, your stylesheet will be merged into the final rendered HTML as inline style-attributes for maximum compatibility (even with Gmail)!.

The default stylesheet used is located under `vendor/cleentfaar/mailer-bundle/Resources/mailer/stylesheet.css.twig`.
You can define your own stylesheet under the `stylesheet` configuration option:
```yaml
# app/config/config.yml
cl_mailer:
  defaults:
    stylesheet: AppBundle:mailer:stylesheet.css.twig
```

## Attributions

- [MailChimp HTML & CSS](http://templates.mailchimp.com/development/) - For the HTML layout and CSS reset that supports most clients.


## Contributing

If you would like to contribute to this package, check out the contribution doc [here](https://github.com/cleentfaar/CLMailerBundle/blob/master/Resources/doc/contributing.md).

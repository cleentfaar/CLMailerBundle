# Installation

## Step 1) Get the bundle

First you need to get a hold of this bundle. There are two ways to do this:

### Method a) Using composer

Add the following to your ``composer.json`` (see http://getcomposer.org/)

    "require" :  {
        "cleentfaar/mailer-bundle": "dev-master"
    }


### Method b) Using submodules

Run the following commands to bring in the needed libraries as submodules.

```bash
git submodule add https://github.com/cleentfaar/CLMailerBundle.git vendor/bundles/CL/Bundle/MailerBundle
```


## Step 2) Register the namespaces

If you installed the bundle by composer, use the created autoload.php  (jump to step 3).
Otherwise, add the following two namespace entries to the `registerNamespaces` call in your autoloader:

``` php
<?php
// app/autoload.php
$loader->registerNamespaces(array(
    // ...
    'Knp\Bundle\MarkdownBundle' => __DIR__.'/../vendor/bundles/knp/markdown-bundle',  // required if you are using the default layouts
    'CL\Bundle\MailerBundle'    => __DIR__.'/../vendor/bundles/cleentfaar/mailer-bundle',
    // ...
));
```


## Step 3) Register the bundle

To start using the bundle, register it in your Kernel (note the required `JMSSerializerBundle`).

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        // required for this bundle, but you probably already have this in your kernel...
        new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),

        // not required for this bundle, although you probably already have this in your kernel...
        // you need this bundle if you want to use (extend) the default Twig-layouts and
        // have set the `cl_mailer.twig` option to true (default)
        new Symfony\Bundle\TwigBundle\TwigBundle(),

        // not required for this bundle, but you might want to use markdown in your templates...
        // with the added markdown-filter you can design one template and use it for both HTML
        // and plain-text formats
        new Knp\Bundle\MarkdownBundle\KnpMarkdownBundle(),

        // this bundle, obviously this IS required
        new CL\Bundle\MailerBundle\CLMailerBundle(),
        // ...
    );
}
```


# Ready?

Either check out the [configuration reference](https://github.com/cleentfaar/CLMailerBundle/blob/master/Resources/doc/configuration.md) to see how you can configure your own mail-types.
Or... jump straight in to a real-world example found in the [usage documentation](https://github.com/cleentfaar/CLMailerBundle/blob/master/Resources/doc/usage.md)!

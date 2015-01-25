# Configuration

The bundle does not require you to define any configuration to start with (default values are used).
Below is a reference of the default values that are set when you enable this bundle.
```yaml
# app/config/config.yml
cl_mailer:
  twig: true # whether or not to use twig as the rendering engine (default is true, false uses php)
  defaults:
    html_layout: CLMailerBundle:mailer:layout.html.twig
    plain_text_layout: CLMailerBundle:mailer:layout.txt.twig
    stylesheet: CLMailerBundle:mailer:stylesheet.css.twig
```

It may be useful if you set a default from-email and from-name so that every e-mail you send will at least have a (safe)
value for it:
```yaml
cl_mailer:
  defaults:
    # ...
    from_email: your.default.from@email.com
    from_name: Your Name
```


# Ready?

Let's start sending e-mails! Check out the [usage documentation](https://github.com/cleentfaar/CLMailerBundle/blob/master/Resources/doc/usage.md)!

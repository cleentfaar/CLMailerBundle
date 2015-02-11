# Preview
## Previewing an e-mail in your browser

In this example I will show you how you could test an e-mail in your browser, to see if e.g. your CSS renders
correctly.

### 1. Create a new controller, extending `AbstractMailerPreviewController`
```php
<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use CL\Bundle\MailerBundle\Controller\MailerPreviewController as BaseMailerPreviewController;

class MailerPreviewController extends BaseMailerPreviewController
{
    /**
     * @Route("/mailer/preview/{type}/", name="cl_mailer_preview")
     * @Method({"GET"})
     */
    public function previewAction($type)
    {
        return parent::previewAction($type);
    }

    /**
     * {@inheritdoc}
     */
    protected function createMessage($mailer, $type)
    {
        switch ($type) {
            case 'welcome':
                return $mailer->composeWelcomeMail('foobar', 'john@doe.com');
        }
    }
}

```

**NOTE:** Replace the `switch` above with the types you would like to preview, and return the message your mailer creates.
The reason it works like this is that you may have special variables that you use in your templates, and that you would like
to have previewed as well.


### 2. See the result

Visit `/mailer/preview/{your_mailer_type_here}/`. Or whatever URL you have configured your controller to have.

You should now see the HTML version of the e-mail belonging to the given type.
If you have configured a stylesheet, it should have converted the CSS to inline style-attributes automatically!


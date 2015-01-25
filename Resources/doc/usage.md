# Usage

## Sending a contact mail

In this example I will assume you have a site that let's users send you an e-mail through some sort of contact-form.

### 1. Define the type's options:
```yaml
# app/config/config.yml
cl_mailer:
  # ...
  types:
    my_app_contact_mail:
      template: YourAppBundle:mailer:contact.md.twig
      subject: Someone wants to say hi! # see below
      to_email: contact@yourapp.com # see below
```

- The value for `subject` can be a translatable message ID, just make sure you define the translation under the `mailer` domain.
- The use the `to_email` option is not necessary for every type, i.e. in some cases you might want to pass a value
which is only available when sending the e-mail. For a contact form however, we always want the TO-address to be the same.


### 2. Create a class that is dedicated to sending contact mails:

```php
<?php
// AppBundle\Mailer\ContactMailer.php

namespace AppBundle\Mailer;

use AppBundle\Entity\ContactForm;
use CL\Bundle\MailerBundle\Mailer\AbstractMailer;

class ContactMailer extends AbstractMailer
{
    /**
     * @param ContactForm $contactForm
     *
     * @return \Swift_Message
     */
    public function composeContactFormMail(ContactForm $contactForm)
    {
        return $this->helper->compose('my_app_contact_mail', ['contact_form' => $contactForm]);
    }

    /**
     * @param ContactForm $form
     *
     * @return boolean
     */
    public function sendContactFormMail(ContactForm $form)
    {
        return $this->helper->send($this->composeContactFormMail($form));
    }
}
```

**NOTE:** The code above shows you how you could split-up the process of creating the e-mail, and actually sending it,
into two separate methods. However, it's perfectly fine to just do it all in one method if that suits you.


### 3. Define a service so we can access this mailer in our application:

```yaml
# src/AppBundle/Resources/config/services.yml
services:
  app.mailer.contact:
    class: AppBundle\Mailer\ContactMailer
    tags:
      - { name: cl_mailer.mailer }
```


### 4. Create a template for this e-mail:

We also need to create the template that we configured in [step 1](#1-define-the-types-options).
Here is just an example of what that could look like (assuming you had passed the `contactForm`-variable when you composed the e-mail).

```twig
{# src/AppBundle/Resources/views/mailer/contact.md.twig` (path for this example) #}
{% extends layout %}

{%- block content -%}
  A contact form has been submitted through [yoursite.com]({{ url('my_homepage') }}).

  | Option   | Value                            |
  |----------|----------------------------------|
  | Name     | {{ contact_form.name }}          |
  | E-mail   | {{ contact_form.email }}         |
  | IP       | {{ contact_form.remoteAddress }} |

  **Message:**
  {{ contact_form.message }}

  {% set signature = include('AppBundle:mailer:signature.md.twig') -%}
  {{ signature|markdown }}
{%- endblock -%}

```

#### A few pointers:
- The `{% extends layout %}` call here will extend whichever layout (HTML or plain-text) is currently being rendered.
Just remember to include it and you should be fine.
  - If you use this approach you can use markdown to mark-up your e-mail! Depending on the layout it is automatically
  converted to HTML or converted to plain-text afterwards.
- The inclusion of a signature is optional obviously, but you probably will need some way of signing your e-mails if you
are planning on sending a lot of different e-mails.


### 5. Final step: sending the e-mail (i.e. within a controller)!

Assuming you have a controller named `ContactController`, which has an action that processes a contact-form,
and sends the e-mail if the form is deemed valid:

```php
<?php

namespace AppBundle\Controller;

use AppBundle\Entity\ContactForm;
use AppBundle\Form\Type\ContactType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\PreconditionFailedHttpException;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact/", name="contact")
     * @Method({"POST", "GET"})
     */
    public function indexAction(Request $request)
    {
        $contactForm = new ContactForm();
        $contactForm->setDatetimeCreated(new \DateTime());
        $contactForm->setRemoteAddress($request->getClientIp());

        $form = $this->createForm(new ContactType(), $contactForm);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $contactMailer = $this->get('app.mailer.contact');
                $contactMailer->sendContactFormMail($contactForm);

                $this->getDoctrine()->getManager()->persist($contactForm);
                $this->getDoctrine()->getManager()->flush();

                $this->addFlash('success', 'Thanks for your message! I will try to reply as soon as possible!');

                return $this->redirect($this->generateUrl('contact'));
            }
        }

        return $this->render('AppBundle:contact:index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
```

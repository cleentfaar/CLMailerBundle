<?php

namespace CL\Bundle\MailerBundle\Mailer;

class WelcomeMailer extends AbstractMailer
{
    /**
     * @param string $foo
     * @param string $to
     *
     * @return \Swift_Message
     */
    public function composeWelcomeMail($foo, $to)
    {
        return $this->helper->compose('welcome', ['foo' => $foo], $to);
    }

    /**
     * @param string $foo
     * @param string $to
     *
     * @return boolean
     */
    public function sendWelcomeMail($foo, $to)
    {
        return $this->helper->send($this->composeWelcomeMail($foo, $to));
    }
}

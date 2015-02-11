<?php

namespace CL\Bundle\MailerBundle\Util;

use CL\Bundle\MailerBundle\Mailer\AbstractMailer;
use CL\Bundle\MailerBundle\Util\MessageRenderer;

class MailerPreviewer
{
    /**
     * @var MessageRenderer
     */
    private $messageRenderer;

    /**
     * @param MessageRenderer $messageRenderer
     */
    public function __construct(MessageRenderer $messageRenderer)
    {
        $this->messageRenderer = $messageRenderer;
    }

    /**
     * @param AbstractMailer $mailer
     * @param string         $composeMethod
     * @param array          $args
     *
     * @return string
     */
    public function preview(AbstractMailer $mailer, $composeMethod, array &$args = [])
    {
        /** @var \Swift_Message $message */
        $message = call_user_func_array([$mailer, $composeMethod], $args);

        return $message->getBody();
    }
}

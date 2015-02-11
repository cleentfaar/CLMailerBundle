<?php

namespace CL\Bundle\MailerBundle\Util;

class MessagePreviewer
{
    /**
     * @param \Swift_Message $message
     *
     * @return string
     */
    public function preview(\Swift_Message $message)
    {
        return $message->getBody();
    }
}

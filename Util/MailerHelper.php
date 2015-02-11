<?php

namespace CL\Bundle\MailerBundle\Util;

class MailerHelper
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @var MessageFactory
     */
    private $messageFactory;

    /**
     * @param \Swift_Mailer  $mailer
     * @param MessageFactory $messageFactory
     */
    public function __construct(\Swift_Mailer $mailer, MessageFactory $messageFactory)
    {
        $this->mailer         = $mailer;
        $this->messageFactory = $messageFactory;
    }

    /**
     * @param \Swift_Message $message
     *
     * @return bool
     */
    public function send(\Swift_Message $message)
    {
        return $this->mailer->send($message) > 0;
    }

    /**
     * Composes a message to be sent.
     *
     * @param string       $type          An identifier for this type of message, it determines the template to and is used in translation formatting etc.
     * @param array        $variables     Variables to be used inside the template
     * @param array|string $to            Recipient, in the form of a string (e-mail) or array (`email => name`). If no recipient is given, the default is used
     * @param array|string $from          Sender, in the form of a string (e-mail) or an array (`email => name`). If no sender is given, the default is used
     * @param string|null  $subject       The translation ID to use for the subject of this message, leave as null to use the type's default
     * @param array|null   $subjectParams The translation parameters to use for the subject of this message
     *
     * @return \Swift_Message
     */
    public function compose($type, array $variables = [], $to = null, $from = null, $subject = null, array $subjectParams = null)
    {
        $message = $this->messageFactory->create($type, $from, $to, $subject, $subjectParams, $variables);

        return $message;
    }

    /**
     * @param string $type
     * @param array  $variables
     * @param null   $to
     * @param null   $from
     * @param null   $subject
     * @param array  $subjectParams
     *
     * @return bool
     */
    public function composeAndSend($type, array $variables = [], $to = null, $from = null, $subject = null, array $subjectParams = null)
    {
        return $this->send($this->compose($type, $variables, $to, $from, $subject, $subjectParams));
    }
}

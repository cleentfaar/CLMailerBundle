<?php

namespace CL\Bundle\MailerBundle\Mailer;

use CL\Bundle\MailerBundle\Util\MailerHelper;

abstract class AbstractMailer implements MailerInterface
{
    /**
     * @var MailerHelper
     */
    protected $helper;

    /**
     * @param MailerHelper $helper
     */
    public function __construct(MailerHelper $helper)
    {
        $this->helper = $helper;
    }
}

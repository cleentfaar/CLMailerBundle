<?php

namespace CL\Bundle\MailerBundle\Mailer;

use CL\Bundle\MailerBundle\Util\MailerHelper;

abstract class AbstractMailer
{
    /**
     * @var MailerHelper
     */
    protected $helper;

    /**
     * @param MailerHelper $helper
     */
    public function setHelper(MailerHelper $helper)
    {
        $this->helper = $helper;
    }
}

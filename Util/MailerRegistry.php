<?php

namespace CL\Bundle\MailerBundle\Util;

use CL\Bundle\MailerBundle\Mailer\MailerInterface;

class MailerRegistry
{
    /**
     * @var MailerInterface[]
     */
    private $mailers = [];

    /**
     * @var array
     */
    private $options = [];

    /**
     * @param string          $type
     * @param MailerInterface $mailer
     * @param array           $options
     */
    public function register($type, MailerInterface $mailer, array $options)
    {
        if (!is_object($mailer)) {
            throw new \InvalidArgumentException(sprintf('Mailers must be objects: %s given', gettype($mailer)));
        }

        $this->mailers[$type] = $mailer;
        $this->options[$type] = $options;
    }

    /**
     * @param string $type
     *
     * @return MailerInterface
     */
    public function get($type)
    {
        return $this->mailers[$type];
    }

    /**
     * @param string $type
     *
     * @return array
     */
    public function getOptions($type)
    {
        return $this->options[$type];
    }
}

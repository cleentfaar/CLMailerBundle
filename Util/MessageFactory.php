<?php

namespace CL\Bundle\MailerBundle\Util;

use Symfony\Component\Translation\TranslatorInterface;

class MessageFactory
{
    /**
     * @var MailerRegistry
     */
    private $mailerRegistry;

    /**
     * @var MessageRenderer
     */
    private $renderer;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @param MailerRegistry      $mailerRegistry
     * @param MessageRenderer     $messageRenderer
     * @param TranslatorInterface $translator
     */
    public function __construct(
        MailerRegistry $mailerRegistry,
        MessageRenderer $messageRenderer,
        TranslatorInterface $translator
    ) {
        $this->mailerRegistry = $mailerRegistry;
        $this->renderer       = $messageRenderer;
        $this->translator     = $translator;
    }

    /**
     * @param string            $type
     * @param string|array|null $from
     * @param string|array|null $to
     * @param string|null       $subject
     * @param array|null        $subjectParams
     * @param array             $variables
     *
     * @return \Swift_Message
     */
    public function create($type, $from = null, $to = null, $subject = null, array $subjectParams = null, array $variables = [])
    {
        if ($subject === null) {
            $subject = $this->getTypeOption($type, 'subject');
        }

        if ($subjectParams === null) {
            $subjectParams = [];
        }

        $translatedSubject = $this->translator->trans($subject, $subjectParams, 'mailer');
        if ($translatedSubject === $subject) {
            $translatedSubject = null;
        }

        $message = \Swift_Message::newInstance();
        $message->setSubject($translatedSubject);
        $message->setFrom($this->determineFrom($type, $from));
        $message->setTo($this->determineTo($type, $to));

        $variables['title'] = $translatedSubject;
        $template           = $this->getTypeOption($type, 'template');
        $htmlLayout         = $this->getTypeOption($type, 'html_layout');
        $plainTextLayout    = $this->getTypeOption($type, 'plain_text_layout');
        $html               = $this->renderer->renderHtml($template, $htmlLayout, $variables, $this->getTypeOption($type, 'stylesheet'));
        $plainText          = $this->renderer->renderPlainText($template, $plainTextLayout, $variables);

        $message->setBody($html, 'text/html');
        $message->addPart($plainText, 'text/plain');

        return $message;
    }

    /**
     * @param string            $type
     * @param string|array|null $givenFrom
     *
     * @return array|string|null
     */
    private function determineFrom($type, $givenFrom = null)
    {
        if ($givenFrom !== null) {
            return $givenFrom;
        }

        if ($this->hasTypeOption($type, 'from_name')) {
            return [
                $this->getTypeOption($type, 'from_email') => $this->getTypeOption($type, 'from_name')
            ];
        }

        return $this->getTypeOption($type, 'from_email');
    }

    /**
     * @param string            $type
     * @param string|array|null $givenTo
     *
     * @return array|string|null
     */
    private function determineTo($type, $givenTo = null)
    {
        if ($givenTo !== null) {
            return $givenTo;
        }

        if ($this->hasTypeOption($type, 'to_name')) {
            return [
                $this->getTypeOption($type, 'to_email') => $this->getTypeOption($type, 'to_name')
            ];
        }

        return $this->getTypeOption($type, 'to_email');
    }

    /**
     * @param string $type
     * @param string $option
     *
     * @return bool
     */
    private function hasTypeOption($type, $option)
    {
        $typeOptions = $this->mailerRegistry->getOptions($type);

        return array_key_exists($option, $typeOptions) && $typeOptions[$option] !== null;
    }

    /**
     * @param $type
     * @param $option
     *
     * @return mixed
     */
    private function getTypeOption($type, $option)
    {
        $typeOptions = $this->mailerRegistry->getOptions($type);

        if (!array_key_exists($option, $typeOptions)) {
            throw new \InvalidArgumentException(sprintf(
                'There is no option with that name (%s) for this type: %s. Available options are: %s. ' .
                'You can also add it to the default options and forgettaboutit :)',
                $option,
                $type,
                implode(', ', array_keys($typeOptions))
            ));
        }

        return $typeOptions[$option];
    }
}

<?php

namespace CL\Bundle\MailerBundle\Mailer;

use Symfony\Component\Translation\TranslatorInterface;

class MessageFactory
{
    /**
     * @var MessageRenderer
     */
    private $renderer;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var array
     */
    private $types;

    /**
     * @param MessageRenderer     $messageRenderer
     * @param TranslatorInterface $translator
     * @param array               $types
     * @param array               $defaultOptions
     */
    public function __construct(
        MessageRenderer $messageRenderer,
        TranslatorInterface $translator,
        array $types,
        array $defaultOptions = []
    ) {
        $this->renderer   = $messageRenderer;
        $this->translator = $translator;
        $this->types      = $this->mergeTypes($types, $defaultOptions);
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
        $html               = $this->renderer->renderHtml($template, $htmlLayout, $variables);
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
        $typeOptions = $this->getTypeOptions($type);

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
        $typeOptions = $this->getTypeOptions($type);

        if (!array_key_exists($option, $typeOptions)) {
            throw new \InvalidArgumentException(sprintf(
                'There is no option with that name (%s) for this type: %s. Available options are: %s',
                $option,
                $type,
                implode(', ', array_keys($typeOptions))
            ));
        }

        return $typeOptions[$option];
    }

    /**
     * @param string $type
     *
     * @return array
     */
    private function getTypeOptions($type)
    {
        if (!is_string($type)) {
            throw new \InvalidArgumentException(sprintf(
                'Type must be a string, got: %s',
                gettype($type)
            ));
        }

        if (!array_key_exists($type, $this->types)) {
            throw new \InvalidArgumentException(sprintf(
                'There is no type of e-mail with that name: %s. Available types are: %s',
                $type,
                implode(', ', array_keys($this->types))
            ));
        }

        return $this->types[$type];
    }

    /**
     * @param array $types
     * @param array $defaultOptions
     *
     * @return array
     */
    private function mergeTypes(array $types, array $defaultOptions)
    {
        foreach ($types as $type => $options) {
            $types[$type] = array_merge($defaultOptions, $options);
        }

        return $types;
    }
}

<?php

namespace CL\Bundle\MailerBundle\Twig;

class MarkdownFallbackExtension extends \Twig_Extension
{
    /**
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * @param \Twig_Environment $twig
     */
    public function __construct(\Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'twig_extension_markdown_fallback';
    }

    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('markdown_fallback', [&$this, 'markdownFallback'], ['is_safe' => ['html']]),
            new \Twig_SimpleFilter('markdown_fallback_stripped', [&$this, 'markdownFallbackStripped']),
        ];
    }

    /**
     * @param string $markdownText
     *
     * @return string|null
     */
    public function markdownFallback($markdownText)
    {
        $markdownText = trim($markdownText);

        if ($filter = $this->twig->getFilter('markdown')) {
            $html = call_user_func($filter->getCallable(), $markdownText);
        } else {
            $html = $markdownText;
        }

        return $html ?: null;
    }

    /**
     * @param string $markdownText
     *
     * @return string|null
     */
    public function markdownFallbackStripped($markdownText)
    {
        if ($html = $this->markdownFallback($markdownText)) {
            return strip_tags($html);
        }

        return null;
    }
}

<?php

namespace CL\Bundle\MailerBundle\Util;

use Symfony\Component\Templating\EngineInterface;
use TijsVerkoyen\CssToInlineStyles\CssToInlineStyles;

class MessageRenderer
{
    /**
     * @var EngineInterface
     */
    private $engine;

    /**
     * @param EngineInterface $engine
     */
    public function __construct(EngineInterface $engine)
    {
        $this->engine = $engine;
    }

    /**
     * @param string $template
     * @param string $layout
     * @param array  $variables
     *
     * @return string
     */
    public function renderPlainText($template, $layout, array $variables = [])
    {
        $html = $this->doRenderHtml($template, $layout, $variables);
        $text = strip_tags($html);

        return $text;
    }

    /**
     * @param string      $template
     * @param string      $layout
     * @param array       $variables
     * @param string|null $stylesheet
     *
     * @return string
     */
    public function renderHtml($template, $layout, array $variables = [], $stylesheet = null)
    {
        $html = $this->doRenderHtml($template, $layout, $variables);

        if ($stylesheet !== null) {
            $css             = file_get_contents($stylesheet);
            $cssToInlineHtml = new CssToInlineStyles($html, $css);

            return $cssToInlineHtml->convert();
        }

        return $html;
    }

    /**
     * @param string $template
     * @param string $layout
     * @param array  $variables
     *
     * @return string
     *
     * @throws \Exception
     */
    public function doRenderHtml($template, $layout, array $variables)
    {
        $variables['layout'] = $layout;
        $htmlContent         = $this->engine->render($template, $variables);

        return $htmlContent;
    }
}

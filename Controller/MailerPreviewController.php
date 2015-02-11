<?php

namespace CL\Bundle\MailerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

abstract class MailerPreviewController extends Controller
{
    public function previewAction($type)
    {
        $mailer  = $this->get('cl_mailer.util.mailer_registry')->get($type);
        $message = $this->createMessage($mailer, $type);
        $html    = $this->get('cl_mailer.util.message_previewer')->preview($message);

        return $this->render('CLMailerBundle:mailer:preview.html.twig', [
            'html' => $html,
        ]);
    }

    /**
     * @param object $mailer
     * @param string $type
     *
     * @return \Swift_Message
     */
    abstract protected function createMessage($mailer, $type);
}

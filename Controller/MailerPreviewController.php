<?php

namespace CL\Bundle\MailerBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class MailerPreviewController extends Controller
{
    /**
     * @Route("/mailer/preview/{key}/", name="cl_mailer_preview")
     * @Method({"GET"})
     */
    public function indexAction(Request $request, $key)
    {
        $html = $this->get('cl_mailer.previewer')->preview($key, 'html');

        return $this->render('CLMailer:mailer:preview.html.twig', [
            'html' => $html,
        ]);
    }
}

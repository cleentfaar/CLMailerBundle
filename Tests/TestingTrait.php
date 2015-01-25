<?php

namespace CL\Bundle\MailerBundle\Tests;

trait TestingTrait
{
    /**
     * @return array
     */
    public function getTestingDefaults()
    {
        return [
            'from_name'         => 'Foo Bar',
            'from_email'        => 'foo@bar.com',
            'to_name'           => 'Apple Pie',
            'to_email'          => 'apple@pie.com',
            'html_layout'       => 'path/to/html-layout',
            'plain_text_layout' => 'path/to/plain-text-layout',
            'stylesheet'        => 'path/to/stylesheet',
        ];
    }

    protected function getTestingType()
    {
        return [
            'from_name'  => 'John Doe',
            'from_email' => 'john@doe.com',
            'template'   => 'path/to/template',
            'subject'    => 'This is the subject of this e-mail',
        ];
    }
}

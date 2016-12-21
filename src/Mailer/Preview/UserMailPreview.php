<?php
namespace App\Mailer\Preview;

use Josegonzalez\MailPreview\Mailer\Preview\MailPreview;

class UserMailPreview extends MailPreview
{
    public function forgotPassword()
    {
        return $this->getMailer('User')
                    ->preview('forgotPassword', [
                        'example@example.com',
                        'some-test-token'
                    ]);
    }
}

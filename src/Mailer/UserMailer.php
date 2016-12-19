<?php
namespace App\Mailer;

use Cake\Mailer\Mailer;

class UserMailer extends Mailer
{
    /**
     * Email sent on password recovery requests
     *
     * @param array $email User email
     * @param string $token Token used for validation
     * @return \Cake\Mailer\Mailer
     */
    public function forgotPassword($email, $token)
    {
        return $this->to($email)
            ->subject('Reset your password')
            ->template('forgot_password')
            ->layout(false)
            ->set([
                'token' => $token,
            ])
            ->emailFormat('html');
    }
}

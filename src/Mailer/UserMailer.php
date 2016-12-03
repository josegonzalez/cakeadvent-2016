<?php
namespace App\Mailer;

use Cake\Mailer\Mailer;

class UserMailer extends Mailer
{
    /**
     * Email sent on password recovery requests
     *
     * @param array $user User information, must includer email and username
     * @param string $token Token used for validation
     * @return \Cake\Mailer\Mailer
     */
    public function forgotPassword($user, $token)
    {
        return $this->to($user['email'])
            ->subject('Reset your password')
            ->template('forgot_password')
            ->layout(false)
            ->set([
                'token' => $token,
            ])
            ->emailFormat('html');
    }
}

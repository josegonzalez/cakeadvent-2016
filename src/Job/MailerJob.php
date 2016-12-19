<?php
namespace App\Job;

use Cake\Log\LogTrait;
use Cake\Mailer\MailerAwareTrait;
use josegonzalez\Queuesadilla\Job\Base as JobContainer;

class MailerJob
{
    use LogTrait;
    use MailerAwareTrait;

    public function execute(JobContainer $job)
    {
        $mailer = $job->data('mailer');
        $action = $job->data('action');
        $data = $job->data('data', []);

        if (empty($mailer)) {
            $this->log('Missing mailer in job config');
            return;
        }

        if (empty($action)) {
            $this->log('Missing action in job config');
            return;
        }

        $this->getMailer($mailer)->send($action, $data);
    }
}

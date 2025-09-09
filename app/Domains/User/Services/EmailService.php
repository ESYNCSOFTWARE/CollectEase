<?php

declare(strict_types=1);

namespace App\Domains\User\Services;

use App\Domains\User\Events\EmailVerification;
use App\Domains\User\Listeners\SendEmailVerification;

class EmailService
{
    public function sendVerificationEmail($user): void
    {
        $emailVerification = new EmailVerification($user);
        $sendEmailVerification = new SendEmailVerification;
        $sendEmailVerification->handle($emailVerification);
    }
}

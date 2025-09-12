<?php

declare(strict_types=1);

namespace App\Domains\User\Listeners;

use App\Domains\User\Events\EmailVerification;

class SendEmailVerification
{
    public function handle(EmailVerification $emailVerification): void
    {

        // Mail::to($emailVerification->user->email)
        //     ->send(new EmailVerificationMail($emailVerification->user));
    }
}

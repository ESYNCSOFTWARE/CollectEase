<?php

declare(strict_types=1);

namespace App\Domains\User\Events;

use App\Domains\User\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class EmailVerification
{
    use Dispatchable;
    use SerializesModels;

    public User $user;

    public function __construct(User $user)
    {

        $this->user = $user;
    }
}

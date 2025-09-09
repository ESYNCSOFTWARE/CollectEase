<?php

declare(strict_types=1);

namespace App\Domains\User\Controllers;

use App\Domains\User\Requests\ProfileUpdateRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): Response
    {
        return Inertia::render('Profile/Edit');
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $profileUpdateRequest): RedirectResponse
    {
        $profileUpdateRequest->user()->fill($profileUpdateRequest->validated());

        if ($profileUpdateRequest->user()->isDirty('email')) {
            $profileUpdateRequest->user()->email_verified_at = null;
        }

        $profileUpdateRequest->user()->save();

        return Redirect::route('profile.edit');
    }

    public function changePassword(): Response
    {
        return Inertia::render('Profile/ChangePassword');
    }
}

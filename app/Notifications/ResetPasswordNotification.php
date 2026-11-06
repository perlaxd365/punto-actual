<?php

namespace App\Notifications;

use App\Mail\ResetPasswordMail;
use Illuminate\Auth\Notifications\ResetPassword as BaseNotification;

class ResetPasswordNotification extends BaseNotification
{
    public function toMail($notifiable)
    {
        $expires = (int) config('auth.passwords.'.config('auth.defaults.passwords').'.expire');

        return (new ResetPasswordMail(
            $notifiable->getEmailForPasswordReset(),
            $this->resetUrl($notifiable),
            $expires,
        ))->to($notifiable->getEmailForPasswordReset());
    }
}

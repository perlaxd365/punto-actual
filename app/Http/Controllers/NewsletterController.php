<?php

namespace App\Http\Controllers;

use App\Mail\NewsletterWelcome;
use App\Models\Subscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Throwable;

class NewsletterController extends Controller
{
    public function store(Request $request)
    {
        if ($request->filled('website')) {
            return back()->with('newsletter_status', 'Listo, te suscribiste. Gracias por seguir Punto Actual.');
        }

        $data = $request->validate([
            'email' => 'required|email|max:180',
            'name' => 'nullable|string|max:120',
        ]);

        $email = mb_strtolower(trim($data['email']));

        $subscriber = Subscriber::firstOrCreate(
            ['email' => $email],
            [
                'name' => $data['name'] ?? null,
                'source' => 'web',
                'status' => 'active',
                'ip_address' => $request->ip(),
                'user_agent' => mb_substr((string) $request->userAgent(), 0, 500),
            ]
        );

        if (! $subscriber->wasRecentlyCreated && $subscriber->status !== 'active') {
            $subscriber->update(['status' => 'active']);
        }

        if ($subscriber->wasRecentlyCreated) {
            try {
                Mail::to($subscriber->email)->send(new NewsletterWelcome($subscriber));
            } catch (Throwable $e) {
                report($e);
            }
        }

        return back()->with('newsletter_status', 'Listo, te suscribiste. Revisa tu correo (y la carpeta de spam).');
    }

    public function unsubscribe(Subscriber $subscriber)
    {
        $subscriber->update(['status' => 'unsubscribed']);

        return view('pages.newsletter-unsubscribed', ['subscriber' => $subscriber]);
    }
}

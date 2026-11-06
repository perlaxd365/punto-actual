<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\NewsletterBroadcast;
use App\Models\Subscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Throwable;

class NewsletterCampaignController extends Controller
{
    public function index()
    {
        return view('admin.newsletter.index', [
            'total' => Subscriber::where('status', 'active')->count(),
        ]);
    }

    public function send(Request $request)
    {
        $data = $request->validate([
            'subject' => 'required|string|max:150',
            'body' => 'required|string|max:20000',
        ]);

        $subscribers = Subscriber::where('status', 'active')->orderBy('id')->get();

        if ($subscribers->isEmpty()) {
            return back()->withErrors(['body' => 'No hay suscriptores activos para enviar.']);
        }

        $sent = 0;
        $failed = 0;

        foreach ($subscribers as $subscriber) {
            try {
                Mail::to($subscriber->email)->send(new NewsletterBroadcast($subscriber, $data['subject'], $data['body']));
                $sent++;
            } catch (Throwable $e) {
                $failed++;
                report($e);
            }
        }

        return back()->with('status', "Boletín procesado: {$sent} enviados, {$failed} fallidos.");
    }
}

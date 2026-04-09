<?php

namespace App\Http\Controllers;

use App\Mail\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function send(Request $request)
    {
        $data = $request->validate([
            'name'    => ['required', 'string', 'max:100'],
            'email'   => ['required', 'email', 'max:150'],
            'subject' => ['required', 'string', 'max:200'],
            'message' => ['required', 'string', 'max:2000'],
        ]);

        Mail::to(config('mail.admin_email', 'admin@covoiturage.bj'))
            ->send(new ContactMessage(
                senderName:  $data['name'],
                senderEmail: $data['email'],
                subject:     $data['subject'],
                body:        $data['message'],
            ));

        return back()->with('success', 'Votre message a bien été envoyé. Nous vous répondrons dans les 24 heures.');
    }
}

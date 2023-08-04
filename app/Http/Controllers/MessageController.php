<?php

namespace App\Http\Controllers;

use App\Mail\EnvoyerMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MessageController extends Controller
{
    public function create()
    {
        return view('emails.formulaire');
    }

    public function send(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'message' => 'required'
        ]);

        $email = $request->input('email');
        $message1 = $request->input('message');
        $message2 = mt_rand(10000, 99999);
        $message="Votre code de validation est :".$message2;

        Mail::to($email)->send(new EnvoyerMessage($message));

        return back()->with('success', 'Le message a été envoyé avec succès !');
    }
}

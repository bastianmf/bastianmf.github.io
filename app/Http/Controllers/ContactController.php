<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150'],
            'subject' => ['required', 'string', 'max:150'],
            'message' => ['required', 'string', 'max:3000'],
        ], [
            'name.required' => 'Ingresa tu nombre.',
            'email.required' => 'Ingresa tu correo.',
            'email.email' => 'Ingresa un correo válido.',
            'subject.required' => 'Indica el tipo de proyecto.',
            'message.required' => 'Escribe un mensaje.',
        ]);

        /*
         * Aquí puedes integrar Mail, una notificación, una tabla de contactos
         * o cualquier servicio externo.
         *
         * Ejemplo:
         * Mail::to(config('mail.from.address'))
         *     ->send(new PortfolioContactMail($validated));
         */

        return back()->with(
            'success',
            'Tu mensaje fue recibido correctamente. Te responderé a la brevedad.'
        );
    }
}

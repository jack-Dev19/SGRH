<?php

namespace App\Http\Controllers;

use App\Models\Abonnement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Throwable;

class NewsletterController extends Controller
{
    public function subscribe(Request $request)
    {
        // Validation de l'adresse email
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255',
        ], [
            'email.required' => 'Veuillez saisir votre adresse email.',
            'email.email' => 'Veuillez saisir une adresse email valide.',
            'email.max' => 'Votre adresse email est trop longue.',
        ]);

        if ($validator->fails()) {
            return redirect('/#newsletter')
                ->withErrors($validator)
                ->withInput();
        }

        $email = $request->email;

        // Vérifier si l'email existe déjà
        if (Abonnement::where('email', $email)->exists()) {
            return redirect('/#newsletter')
                ->with('warning', 'Cette adresse email est déjà inscrite à notre newsletter.');
        }

        // Enregistrer l'abonnement
        $abonnement = Abonnement::create([
            'email' => $email,
        ]);

        try {
            // Récupérer le destinataire déjà configuré
            $destinataire = config('services.contact.receiver');

            if (empty($destinataire)) {
                throw new \RuntimeException(
                    'Adresse email destinataire non configurée.'
                );
            }

            // Envoyer la notification avec le SMTP Gmail existant
            Mail::raw(
                "Nouvelle inscription à la newsletter.\n\n"
                . "Adresse email : {$abonnement->email}\n"
                . "Date : " . now()->format('d/m/Y à H:i'),
                function ($message) use ($destinataire) {
                    $message
                        ->to($destinataire)
                        ->subject('Nouvelle inscription - Newsletter Digital Agency');
                }
            );

            return redirect('/#newsletter')
                ->with('success', 'Merci ! Votre inscription à la newsletter est confirmée.');

        } catch (Throwable $e) {
            Log::error(
                'Erreur notification newsletter : ' . $e->getMessage()
            );

            // L'abonnement reste enregistré même si le mail échoue
            return redirect('/#newsletter')
                ->with('warning', 'Votre inscription est enregistrée, mais la notification email n’a pas pu être envoyée.');
        }
    }
}

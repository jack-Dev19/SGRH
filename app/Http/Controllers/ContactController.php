<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\ContactMessageMail;
use Throwable;

class ContactController extends Controller
{
    /**
     * Envoyer le message du formulaire de contact.
     */
    public function send(Request $request)
    {
        // Validation des données du formulaire
        $validated = $request->validate([
            'nom' => 'required|string|max:150',
            'email' => 'required|email|max:255',
            'telephone' => 'nullable|string|max:30',
            'sujet' => 'nullable|string|max:200',
            'message' => 'required|string|max:5000',
            'rgpd' => 'accepted',
        ]);

        try {

            // Adresse email qui reçoit le message
            $destinataire = config('services.contact.receiver');

            // Vérifier que l'adresse destinataire existe
            if (empty($destinataire)) {
                throw new \RuntimeException(
                    'Adresse email destinataire non configurée.'
                );
            }

            // Envoyer le message par Gmail SMTP
            Mail::to($destinataire)
                ->send(new ContactMessageMail($validated));

            // Retourner vers la section Contact après succès
            return redirect('/#contact')
                ->with('success', 'Votre message a été envoyé avec succès !');

        } catch (Throwable $e) {

            // Enregistrer l'erreur dans le fichier log
            Log::error('Erreur envoi formulaire contact : ' . $e->getMessage());

            // Retourner vers la section Contact après erreur
            return redirect('/#contact')
                ->withInput()
                ->with('error', "Une erreur est survenue lors de l'envoi. Veuillez réessayer.");
        }
    }
}

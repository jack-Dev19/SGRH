<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Queue\SerializesModels;

class ContactMessageMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public array $contactData
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Nouveau message depuis le formulaire de contact',

            replyTo: [
                new Address(
                    $this->contactData['email'],
                    $this->contactData['nom']
                )
            ]
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.contact'
        );
    }

    public function attachments(): array
    {
        return [];
    }
}

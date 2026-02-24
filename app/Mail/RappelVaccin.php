<?php

namespace App\Mail;

use App\Models\Rappel;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RappelVaccin extends Mailable
{
    use Queueable, SerializesModels;

    public Rappel $rappel;

    /**
     * Create a new message instance.
     */
    public function __construct(Rappel $rappel)
    {
        $this->rappel = $rappel;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🔔 Rappel Vaccin - ' . $this->rappel->enfant->prenom . ' ' . $this->rappel->enfant->nom,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.rappel-vaccin',
            with: [
                'rappel' => $this->rappel,
                'enfant' => $this->rappel->enfant,
                'vaccin' => $this->rappel->vaccin,
                'parent' => $this->rappel->enfant->user
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UserVerified extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $verified;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, bool $verified)
    {
        $this->user = $user;
        $this->verified = $verified;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $status = $this->verified ? 'Verificada' : 'Desverificada';
        
        return new Envelope(
            subject: "Tu cuenta de Pickntruck ha sido $status",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.user-verified',
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

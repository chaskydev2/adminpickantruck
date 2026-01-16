<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DocumentStatusChanged extends Mailable
{
    use Queueable, SerializesModels;

    public $document;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($document)
    {
        $this->document = $document;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = $this->document->status === 'aprobado' ? 'Tu documento ha sido verificado' : 'Tu documento ha sido rechazado';

        return $this->subject($subject)
                    ->view('emails.document_status_changed')
                    ->with(['document' => $this->document]);
    }
}

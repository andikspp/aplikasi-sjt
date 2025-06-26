<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PembatalanPermintaan extends Mailable
{
    use Queueable, SerializesModels;

    public $adminPengaju;
    public $allowance;

    /**
     * Create a new message instance.
     */
    public function __construct($adminPengaju, $allowance)
    {
        $this->adminPengaju = $adminPengaju;
        $this->allowance = $allowance;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Pembatalan Permintaan Penghapusan Data',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'admin.emails.pembatalan_permintaan',
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

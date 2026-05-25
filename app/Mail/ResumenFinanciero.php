<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;

class ResumenFinanciero extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public $totalIncomes,
        public $totalExpenses,
        public $balance,
        public $label,
        public $start,
        public $end
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Resumen Financiero'
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.resumen',
        );
    }
}
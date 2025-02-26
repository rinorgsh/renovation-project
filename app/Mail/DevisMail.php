<?php

namespace App\Mail;

use App\Models\Devis;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Barryvdh\DomPDF\Facade\Pdf;

class DevisMail extends Mailable
{
    use Queueable, SerializesModels;

    public $devis;

    public function __construct(Devis $devis)
    {
        $this->devis = $devis;
    }

    public function build()
    {
        // Charger les relations nécessaires
        $this->devis->load(['client', 'produits']);

        // Générer le PDF
        $pdf = PDF::loadView('pdfs.devis', [
            'devis' => $this->devis
        ]);

        return $this->subject("Devis #" . $this->devis->numero_devis)
            ->view('emails.devis')
            ->attachData($pdf->output(), "devis-{$this->devis->numero_devis}.pdf");
    }
}
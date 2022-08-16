<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class manifestCreated extends Mailable
{
    use Queueable, SerializesModels;

    public $manifest_id;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($manifest_id)
    {
        $this->manifest_id = $manifest_id;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.manifest-created');
    }
}

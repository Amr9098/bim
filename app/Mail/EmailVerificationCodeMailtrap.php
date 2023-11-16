<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
class EmailVerificationCodeMailtrap extends Mailable
{
    use Queueable, SerializesModels;

    public $otp;
    public $name;

    /**
     * Create a new message instance.
     */
    public function __construct($otp, $name)
    {
        $this->otp = $otp;
        $this->name = $name;
    }

    /**
     * Build the message.
     */
    // public function build()
    // {
    //     $subject = 'Hello ' . $this->name . '! You have successfully registered';
    //     $message = "Hello {$this->name}! You have successfully registered.\nYour OTP code is: {$this->otp}\nThe code is valid for 15 minutes only.";

    //     return $this->subject($subject)
    //         ->text(function ($mail) use ($message) {
    //             $mail->body($message);
    //         });
    // }


    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Hello ' . $this->name . '! You have successfully registered',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'userEmail',
        );
    }
}

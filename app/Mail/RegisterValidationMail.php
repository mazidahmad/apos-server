<?php

namespace App\Mail;

// use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RegisterValidationMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The order instance.
     *
     * @var Order
     */
    public $user;
    public $token;

    /**
     * Create a new message instance.
     *
     * @return void
     */
  
    //JADI SECARA DEFAULT KITA MEMINTA DATA USER
    public function __construct($user,$token)
    {
        $this->user = $user;
        $this->token = $token;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        //KEMUDIAN EMAILNYA ME-LOAD VIEW RESET_PASSWORD DAN PASSING DATA USER
        return $this->view('emails.register_validation');
    }
}
<?php

namespace App\Mail\Admin\Auth;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Queue\SerializesModels;

use App\Models\User;
use App\Models\PasswordReset;

class ResetPassword extends Mailable
{
  use Queueable, SerializesModels;

	public $email;
	public $token;

  /**
   * Create a new message instance.
   *
   * @return void
   */
  public function __construct($user)
  {
		$this->email = $user->email;
		$this->token = PasswordReset::select('token')->where('email', $user->email)->orderBy('created_at', 'DESC')->first()['token'];
  }

  /**
   * Get the message envelope.
   *
   * @return \Illuminate\Mail\Mailables\Envelope
   */
  public function envelope()
  {
		return new Envelope(
      from: new Address($_ENV['MAIL_FROM_ADDRESS'], $_ENV['APP_NAME']),
      subject: 'Reset Password',
    );
  }

  /**
   * Get the message content definition.
   *
   * @return \Illuminate\Mail\Mailables\Content
   */
  public function content()
  {
    return new Content(
      view: 'email.admin.auth.reset-password',
    );
  }

  /**
   * Get the attachments for the message.
   *
   * @return array
   */
  public function attachments()
  {
    return [];
  }
}

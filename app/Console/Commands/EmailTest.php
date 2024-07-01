<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class EmailTest extends Command
{
    protected $signature = 'email:sendPlainText';
    protected $description = 'Send a plain text email for testing';

    public function handle()
    {
        $toEmail = 'almamunb72@gmail.com';
        $subject = 'Subject of the email';
        $message = 'This is the content of the plain text email.';

        // Send the plain text email
        Mail::raw($message, function ($message) use ($toEmail, $subject) {
            $message->to($toEmail)
                    ->subject($subject);
        });

        $this->info('Plain text email sent successfully!');
    }
}

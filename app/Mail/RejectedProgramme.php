<?php

namespace App\Mail;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Storage;

class RejectedProgramme extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $user;
    public $rejection_reason;
    public $programme_url = '/education/programme/';
    
    public function __construct(User $user, $rejection_reason, $programme_id)
    {
        $this->user = $user->load('person');
        $this->rejection_reason = $rejection_reason;
        $this->programme_url .= $programme_id.'/view';
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        //Should get these details from setup
        $fromAddress = 'noreply@afrixcel.co.za';
        $fromName = 'Afrixcel Support';
        $subject = 'Programme Rejected | Osizweni Education & Development Centre';

        $data['support_email'] = 'support@afrixcel.co.za';
        $data['company_name'] = 'Osizweni Education & Development Centre';
        $data['company_logo'] = 'http://osizweni.afrixcel.co.za' . Storage::disk('local')->url('logos/logo.png');

        return $this->view('mails.rejected_programme')
            ->from($fromAddress, $fromName)
            ->subject($subject)
            ->with($data);
    }
}

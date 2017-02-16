<?php

namespace App\Mail;

use App\HRPerson;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Storage;

class ActivityELMApproval extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $person;
    public $activity_url = '/education/activity/';

    public function __construct(HRPerson $person, $activity_id)
    {
        $this->person = $person;
        $this->activity_url .= $activity_id.'/view';
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        //Should get these details from setup
        $fromAddress = 'noreply@osizweni.org.za';
        $fromName = 'Afrixcel Support';
        $subject = 'Approval Needed | Osizweni Education & Development Centre';

        $data['support_email'] = 'support@afrixcel.co.za';
        $data['company_name'] = 'Osizweni Education & Development Centre';
        $data['company_logo'] = url('/') . Storage::disk('local')->url('logos/logo.png');

        return $this->view('mails.activity_elm_approval')
            ->from($fromAddress, $fromName)
            ->subject($subject)
            ->with($data);
    }
}

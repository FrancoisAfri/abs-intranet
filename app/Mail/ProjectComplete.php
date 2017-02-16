<?php

namespace App\Mail;

use App\HRPerson;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Storage;

class ProjectComplete extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $person;
    public $project_url = '/project/view/';
    
    public function __construct(HRPerson $person, $project_id)
    {
        $this->person = $person;
        $this->project_url .= $project_id;
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
        $subject = 'Project Completed | Osizweni Education & Development Centre';

        $data['support_email'] = 'support@afrixcel.co.za';
        $data['company_name'] = 'Osizweni Education & Development Centre';
        $data['company_logo'] = url('/') . Storage::disk('local')->url('logos/logo.png');

        return $this->view('mails.project_complete')
            ->from($fromAddress, $fromName)
            ->subject($subject)
            ->with($data);
    }
}

<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Storage;

class EducatorManagerMail extends Mailable
{
    use Queueable, SerializesModels;
	public $firstname;
	public $projectID;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($firstname, $projectID)
    {
        //
		$this->firstname = $firstname;
		$this->projectID = $projectID;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $fromAddress = 'noreply@afrixcel.co.za';
        $fromName = 'Afrixcel Support';
        $subject = 'New Project created on Osizweni online Educational system.';
        $data['support_email'] = 'support@afrixcel.co.za';
        $data['company_name'] = 'Osizweni Education & Development Centre';
        $data['company_logo'] = 'http://osizweni.afrixcel.co.za/' . Storage::disk('local')->url('logos/logo.png');
		$data['loan_url'] = 'http://osizweni.afrixcel.co.za/project/view/'.$this->projectID.'';
        return $this->view('mails.education_manager')
            ->from($fromAddress, $fromName)
            ->subject($subject)
            ->with($data);
    }
}

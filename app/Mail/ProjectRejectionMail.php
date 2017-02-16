<?php

namespace App\Mail;
use App\projects;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Storage;

class ProjectRejectionMail extends Mailable
{
    use Queueable, SerializesModels;
	public $user;
    public $rejection_reason;
    //public $loan_url = 'http://devloans.afrixcel.co.za/loan/edit/';
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, $rejection_reason, $project_id)
    {
        //
		$this->user = $user->load('person');
        $this->rejection_reason = $rejection_reason;
        //$this->loan_url .= $loan_id.'';
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
        $subject = 'Project Rejection | Osizweni Education & Development Centre';

        $data['support_email'] = 'support@afrixcel.co.za';
        $data['company_name'] = 'Osizweni Education & Development Centre';
        $data['company_logo'] = 'http://osizweni.afrixcel.co.za' . Storage::disk('local')->url('logos/logo.png');

        return $this->view('mails.rejected_project')
            ->from($fromAddress, $fromName)
            ->subject($subject)
            ->with($data);
    }
}

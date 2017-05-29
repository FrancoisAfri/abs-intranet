<?php

namespace App\Mail;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\HRPerson;
use Illuminate\Support\Facades\Storage;

class EmployeeTasks extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
	public $person;
	public $fromAddress;
	public $fromName;
	public $support_email;
    //public $task = '/education/activity/';

    public function __construct(HRPerson $person,$fromAddress='', $fromName='', $support_email='')
    {
        $this->person = $person;
        //$this->activity_url .= $activity_id.'/view';
        $this->fromAddress .= 'noreply@afrixcel.co.za';
        $this->fromName .= 'Afrixcel Business Solutions';
        $this->support_email .= 'support@osizweni.org.za';
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
		//Should get these details from setup
        $subject = 'New Task';

        $data['support_email'] = $this->support_email;
        $data['company_name'] = $this->fromName ;
        $data['company_logo'] = url('/') . Storage::disk('local')->url('logos/logo.png');

        return $this->view('mails.employeeTasks')
            ->from($this->fromAddress, $this->fromName)
            ->subject($subject)
            ->with($data);
    }
}
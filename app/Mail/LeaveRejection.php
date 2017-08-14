<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\HRPerson;
use App\CompanyIdentity;
use App\MeetingMinutes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class LeaveRejection extends Mailable
{
    use Queueable, SerializesModels;

    public $person;
    public $meeting;
    public $urls = '/';
    public $fromAddress;
    public $fromName;
    public $support_email;

    /**
     * Create a new message instance.
     *
     * @return void
     */
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

      
            ->from($this->fromAddress, $this->fromName)
            ->subject($subject)
            ->with($data);
            return $this->view('mails.employeeTasks')
      //  return $this->view('view.name');
    }
}





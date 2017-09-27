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

class emailMinutes extends Mailable
{
    use Queueable, SerializesModels;
	
	public $person;
	public $meeting;
	public $urls = '/';
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($person, MeetingMinutes $meeting)
	{
		$this->person = $person;
		$this->meeting =$meeting;
	}

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $companyDetails = CompanyIdentity::systemSettings();
        $companyName = $companyDetails['company_name'];

		//Should get these details from setup
        $subject = "Meeting Minutes on $companyName online system.";

        $data['support_email'] = $companyDetails['support_email'];
        $data['company_name'] = $companyDetails['full_company_name'] ;
        $data['company_logo'] = url('/') . $companyDetails['company_logo_url'];
		//Get minutes details
		$minutesMeeting = DB::table('meetings_minutes')
		->select('meetings_minutes.*','hr_people.first_name as firstname', 'hr_people.surname as surname')
		->leftJoin('hr_people', 'meetings_minutes.employee_id', '=', 'hr_people.id')
		->where('meetings_minutes.meeting_id', $this->meeting->id)
		->orderBy('meetings_minutes.id')
		->get();
		
		$employeesTasks = DB::table('employee_tasks')
		->select('employee_tasks.*'
				,'hr_people.first_name as firstname', 'hr_people.surname as surname')
		->leftJoin('hr_people', 'employee_tasks.employee_id', '=', 'hr_people.id')
		->where('employee_tasks.task_type', '=', 2)
		->where('employee_tasks.meeting_id', $this->meeting->id)
		->orderBy('employee_tasks.employee_id')
		->orderBy('employee_tasks.order_no')
		->get();
		
		$data['employeesTasks'] = $employeesTasks;
		$data['minutesMeeting'] = $minutesMeeting;
        //$data['meeting'] = $meeting;
        return $this->view('mails.meeting_minutes')
            ->from($companyDetails['mailing_address'], $companyDetails['mailing_name'])
            ->subject($subject)
            ->with($data);
    }
}

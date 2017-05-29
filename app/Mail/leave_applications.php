<?php

namespace App\Mail;

// use App\contacts_company;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Storage;

class leave_applications extends Mailable
{
    use Queueable, SerializesModels;

    public $first_name;
    public $surname;
    public $email;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    // public $user;
   
    // public $company_url = '/contacts/company/';

    public function __construct($first_name, $surname, $email)
    {
        // $this->user = $user->load('person');
        $this->first_name = $first_name;
        $this->surname = $surname;
        $this->email = $email;
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
        $subject = 'Password Reset | NU-LAXMI LEASING';

        $data['support_email'] = 'support@afrixcel.co.za';
        $data['company_name'] = 'Afrixcel';
        $data['company_logo'] = 'http://www.afrixcel.co.za' . Storage::disk('local')->url('logos/logo.jpg');
        $data['profile_url'] = 'http://www.afrixcel.co.za/users/profile';

        return $this->view('mails.leave_application')
            ->from($fromAddress, $fromName)
            ->subject($subject)
            ->with($data);
    }
}

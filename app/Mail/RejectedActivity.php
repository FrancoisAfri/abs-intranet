<?php

namespace App\Mail;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Storage;

class RejectedActivity extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $user;
    public $rejection_reason;
    public $activity_url = '/education/activity/';

    public function __construct(User $user, $rejection_reason, $activity_id)
    {
        $this->user = $user->load('person');
        $this->rejection_reason = $rejection_reason;
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
        $fromAddress = 'noreply@afrixcel.co.za';
        $fromName = 'Afrixcel Support';
        $subject = 'Activity Rejected | Osizweni Education & Development Centre';

        $data['support_email'] = 'support@afrixcel.co.za';
        $data['company_name'] = 'Osizweni Education & Development Centre';
        $data['company_logo'] = url('/') . Storage::disk('local')->url('logos/logo.png');

        return $this->view('mails.rejected_activity')
            ->from($fromAddress, $fromName)
            ->subject($subject)
            ->with($data);
    }
}

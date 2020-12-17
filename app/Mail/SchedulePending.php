<?php

namespace App\Mail;

use App\Models\Schedule;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

class SchedulePending extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @param User $toUser
     * @param Schedule $schedule
     */
    public function __construct(private User $toUser, private Schedule $schedule)
    {
        //
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->subject(Str::ucfirst(__('mail.schedule.subject.pending')));

        $this->to($this->toUser);

        return $this->markdown('mail.schedule.pending', ['schedule' => $this->schedule]);
    }
}

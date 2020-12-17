<?php

namespace App\Mail;

use App\Models\Schedule;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

class ScheduleCancel extends Mailable
{
    use Queueable;
    use SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(private User $current, private User $toUser, private Schedule $schedule)
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
        $this->subject(Str::ucfirst(__('mail.schedule.subject.cancel')));

        $this->to($this->toUser);

        return $this->markdown('mail.schedule.cancel', ['schedule' => $this->schedule, 'by' => $this->current->name]);
    }
}

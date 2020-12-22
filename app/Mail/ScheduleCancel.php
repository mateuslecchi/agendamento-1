<?php

namespace App\Mail;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

class ScheduleCancel extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(
        private string $sendBy,
        private string $toEmail,
        private array $schedule,
        private ?string $toName = null
    )
    {
        //
    }

    public function build(): ScheduleCancel
    {
        $this->subject(Str::ucfirst(__('mail.schedule.subject.cancel')));

        $this->to($this->toEmail, $this->toName);

        return $this->markdown('mail.schedule.cancel', [
            'environment' => Str::ucfirst(__($this->schedule['environment'])),
            'block' => Str::ucfirst(__($this->schedule['block'])),
            'date' => Carbon::parse($this->schedule['date'])->format('d/m/Y'),
            'start_time' => Str::replaceLast(':00', '', $this->schedule['start_time']),
            'end_time' => Str::replaceLast(':00', '', $this->schedule['end_time']),
            'for' => Str::ucfirst(__($this->schedule['for'])),
            'by' => Str::ucfirst(__($this->schedule['by'])),
            'sendBy' => Str::ucfirst(__($this->sendBy))
        ]);
    }
}

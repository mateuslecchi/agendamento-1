<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait NotifyBrowser
{
    public function notifyAlert(string $key)
    {
        $this->dispatchBrowserEvent('notify', [
            'type' => 'alert',
            'text' => Str::ucfirst(__($key))
        ]);
    }

    public function notifySuccessOrError(bool $status, string $success, string $error)
    {
        $status ? $this->notifySuccess($success) : $this->notifyError($error);
    }

    public function notifySuccess(string $key)
    {
        $this->dispatchBrowserEvent('notify', [
            'type' => 'success',
            'text' => Str::ucfirst(__($key))
        ]);
    }

    public function notifyError(string $key)
    {
        $this->dispatchBrowserEvent('notify', [
            'type' => 'error',
            'text' => Str::ucfirst(__($key))
        ]);
    }
}

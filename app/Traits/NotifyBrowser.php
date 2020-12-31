<?php

namespace App\Traits;

trait NotifyBrowser
{
    public function notifyAlert(string $key): void
    {
        $this->dispatchBrowserEvent('notify', [
            'type' => 'alert',
            'text' => Fmt::text($key)
        ]);
    }

    public function notifySuccessOrError(bool $status, string $success, string $error): void
    {
        $status ? $this->notifySuccess($success) : $this->notifyError($error);
    }

    public function notifySuccess(string $key): void
    {
        $this->dispatchBrowserEvent('notify', [
            'type' => 'success',
            'text' => Fmt::text($key)
        ]);
    }

    public function notifyError(string $key): void
    {
        $this->dispatchBrowserEvent('notify', [
            'type' => 'error',
            'text' => Fmt::text($key)
        ]);
    }
}

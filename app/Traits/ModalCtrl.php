<?php

namespace App\Traits;

trait ModalCtrl
{
    public bool $show_modal = false;

    public function modalToggle(): self
    {
        $this->show_modal = !$this->show_modal;
        return $this;
    }

    public function modalIsOpen(): bool
    {
        return $this->show_modal;
    }
}

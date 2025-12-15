<?php

namespace App\View\Components;

use Illuminate\View\Component;

class AlertModal extends Component
{
    public string $title;
    public string $message;
    public string $type;

    public function __construct(
        string $title = 'Informasi',
        string $message = '',
        string $type = 'info' // info | warning | error | success
    ) {
        $this->title = $title;
        $this->message = $message;
        $this->type = $type;
    }

    public function render()
    {
        return view('components.alert-modal');
    }
}

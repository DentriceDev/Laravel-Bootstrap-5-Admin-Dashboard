<?php

namespace App\Http\Livewire;

use Livewire\Component;

class NotificationsMenu extends Component
{
    public function render()
    {
        $notifications = auth()->user()->unreadNotifications;
        return view('livewire.notifications-menu', compact('notifications'));
    }
}

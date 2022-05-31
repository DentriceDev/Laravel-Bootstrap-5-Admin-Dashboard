<?php

namespace App\Http\Livewire;

use Illuminate\Http\Request;
use Livewire\Component;

class NotificationsMenu extends Component
{
    protected $listeners = ['refreshNotifications' => '$refresh'];

    public function markNoticicationsAsRead(Request $request)
    {
        auth()->user()->unreadNotifications
        ->when($request->input('id'), function($query) use ($request) {
            return $query->where('id', $request->input('id'));
        })
        ->markAsRead();

        return response()->noContent();
    }

    public function render()
    {
        $notifications = auth()->user()->unreadNotifications;
        return view('livewire.notifications-menu', compact('notifications'));
    }
}

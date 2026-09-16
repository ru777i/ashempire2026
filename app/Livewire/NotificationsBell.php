<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class NotificationsBell extends Component
{
    public $notifications = [];
    public $nombreNonLues = 0;
     public $id;
    public function mount() {
        $this->id = Auth::user()->apprenant->id;
        $this->chargerNotifications();
    }

    public function chargerNotifications()
    {
        $apprenant = Auth::user()->apprenant;
        $this->notifications = $apprenant->notifications()->latest()->take(10)->get();
        $this->nombreNonLues = $apprenant->notifications->count();
    }

    #[On('echo:App.Models.Apprenant.{id},Illuminate\\Notifications\\Events\\BroadcastNotificationCreated')]
    public function nouvellesNotificationsRecues()
    {
        $this->chargerNotifications();
    }
    public function render() {
       return view('livewire.notifications-bell');
    }
    // public function marquerCommeLue(string $notificationId)
    // {
    //     $notification = Auth::user()->notifications()->find($notificationId);
    //     $notification?->markAsRead();
    // }
    // public function toutMarquerCommeLu()
    // {
    //     Auth::user()->unreadNotifications->markAsRead();
    // }
    // public function render()
    // {
    //     $notifications = Auth::user()
    //         ->notifications()
    //         ->latest()
    //         ->limit(15)
    //         ->get();
    //     return view('livewire.notifications-bell', [
    //         'notifications' => $notifications,
    //         'nombrenombreNonLues' => Auth::user()->unreadNotifications->count(),
    //     ]);
    // }
}

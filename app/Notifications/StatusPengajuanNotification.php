<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class StatusPengajuanNotification extends Notification
{
    use Queueable;

    protected string $status;
    protected ?string $catatan;

    public function __construct(string $status, ?string $catatan = null)
    {
        $this->status = $status;
        $this->catatan = $catatan;
    }

    public function via($notifiable)
    {
        return ['database']; 
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'Pembaruan Status Pengajuan',
            'message' => 'Status pengajuan magang Anda kini: ' . strtoupper($this->status),
            'catatan' => $this->catatan,
        ];
    }
}
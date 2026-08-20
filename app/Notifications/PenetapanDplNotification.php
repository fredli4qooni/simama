<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\MagangApplication;

class PenetapanDplNotification extends Notification
{
    use Queueable;

    protected MagangApplication $pengajuan;

    public function __construct(MagangApplication $pengajuan)
    {
        $this->pengajuan = $pengajuan;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'Penetapan Mahasiswa Bimbingan',
            'message' => 'Anda telah ditetapkan sebagai DPL untuk mahasiswa: ' . $this->pengajuan->user->name,
            'application_id' => $this->pengajuan->id,
        ];
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Consultation;
use App\Models\MagangApplication;
use Illuminate\Http\Request;

class ConsultationController extends Controller
{
    public function store(Request $request, string $applicationId)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $application = MagangApplication::findOrFail($applicationId);

        Consultation::create([
            'magang_application_id' => $application->id,
            'sender_id' => $request->user()->id, 
            'message' => $request->message,
            'is_read' => false,
        ]);

        return redirect()->back()->with('success', 'Pesan berhasil dikirim.');
    }
}
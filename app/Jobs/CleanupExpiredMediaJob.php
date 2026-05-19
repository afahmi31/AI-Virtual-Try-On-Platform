<?php

namespace App\Jobs;

use App\Models\TryOnSession;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Storage;

class CleanupExpiredMediaJob implements ShouldQueue
{
    use Queueable;

    public function handle(): void
    {
        $expiredSessions = TryOnSession::query()
            ->whereNotNull('expires_at')
            ->where('expires_at', '<=', Carbon::now())
            ->whereIn('status', ['completed', 'failed'])
            ->get();

        foreach ($expiredSessions as $session) {
            if ($session->customer_photo_path) {
                Storage::disk('local')->delete($session->customer_photo_path);
            }

            if ($session->result_path) {
                Storage::disk('local')->delete($session->result_path);
            }

            $session->update([
                'status' => 'expired',
                'result_path' => null,
                'customer_photo_path' => '',
            ]);
        }
    }
}

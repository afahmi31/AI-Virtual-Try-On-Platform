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
            $this->deleteMediaPath($session->customer_photo_path);
            $this->deleteMediaPath($session->result_path);

            $session->update([
                'status' => 'expired',
                'result_path' => null,
                'customer_photo_path' => '',
            ]);
        }
    }

    private function deleteMediaPath(?string $path): void
    {
        if (! $path) {
            return;
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return;
        }

        Storage::disk('public')->delete($path);
        Storage::disk('local')->delete($path);
    }
}

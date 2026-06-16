<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\HttpFoundation\Response;

class CronController extends Controller
{
    public function run(Request $request): JsonResponse
    {
        $secret = (string) config('cron.secret', '');
        if ($secret === '') {
            return response()->json([
                'ok' => false,
                'message' => 'Cron is not configured. Set CRON_SECRET in .env on the server.',
            ], Response::HTTP_SERVICE_UNAVAILABLE);
        }

        $token = (string) $request->query('token', '');
        if (! hash_equals($secret, $token)) {
            return response()->json([
                'ok' => false,
                'message' => 'Invalid cron token.',
            ], Response::HTTP_FORBIDDEN);
        }

        Artisan::call('schedule:run', ['--no-interaction' => true]);

        return response()->json([
            'ok' => true,
            'message' => 'Scheduled tasks executed.',
            'output' => trim(Artisan::output()),
            'ran_at' => now()->toIso8601String(),
        ]);
    }
}

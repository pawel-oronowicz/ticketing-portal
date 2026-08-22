<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

class SystemSettingsController
{
    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()->json([
            'registration_enabled' => config('features.registration_enabled'),
        ]);
    }
}

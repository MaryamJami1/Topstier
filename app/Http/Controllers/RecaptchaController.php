<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class RecaptchaController extends Controller
{
    public function verify(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
            'action' => 'nullable|string'
        ]);

        $secret = config('services.recaptcha.secret');
        $score = config('services.recaptcha.score');
        $token = $request->input('token');
        $expectedAction = $request->input('action'); 

        $response = Http::asForm()->post(
            'https://www.google.com/recaptcha/api/siteverify',
            [
                'secret'   => $secret,
                'response' => $token,
            ]
        );

        $result = $response->json();

        // Build response
        if ($result['success'] === true &&  $result['score']  >= $score) {
            if ($expectedAction && $result['action'] !== $expectedAction) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid reCAPTCHA action.',
                ], 403);
            }

            //  return response()->json([
            //     'success' => false,
            //     'score'   => $result['score'],
            //     'action'  => $result['action'],
            //     'message' => 'Invalid reCAPTCHA action.',
            // ]);

            return response()->json([
                'success' => true,
                'score'   => $result['score'],
                'action'  => $result['action'],
                'message' => 'Token verified successfully',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'reCAPTCHA verification failed',
            'errors'  => $result
        ], 403);
    }
}

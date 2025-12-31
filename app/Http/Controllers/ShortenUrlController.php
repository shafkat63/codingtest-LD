<?php

namespace App\Http\Controllers;

use App\Models\ShortenedUrl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ShortenUrlController extends Controller
{
    public function shorten(Request $request)
    {


        $validator = Validator::make($request->all(), [
            'original_url' => 'required|url|max:2048',
        ]);

        if ($validator->fails()) {
            $errors = [];
            foreach ($validator->errors()->messages() as $field => $messages) {
                foreach ($messages as $message) {
                    $errors[] = [
                        'field' => $field,
                        'error_code' => $this->mapValidationError($field, $message),
                        'message' => $message
                    ];
                }
            }

            return response()->json([
                'success' => false,
                'errors' => $errors
            ], 200);
        }

        $existing = ShortenedUrl::where('user_id', $request->user()->id)
            ->where('original_url', $request->original_url)
            ->first();

        if ($existing) {
            return response()->json([
                'success' => false,
                'error_code' => 1001,
                'message' => 'This URL has already been shortened.',
                'short_code' => $existing->short_code
            ], 200);
        }

        do {
            $shortCode = Str::random(6);
        } while (ShortenedUrl::where('short_code', $shortCode)->exists());

        $shortenedUrl = ShortenedUrl::create([
            'user_id' => $request->user()->id,
            'original_url' => $request->original_url,
            'short_code' => $shortCode,
        ]);

        return response()->json([
            'success' => true,
            'short_code' => $shortenedUrl->short_code
        ], 200);
    }

    public function redirectToOriginal($shortCode)
    {
        $url = ShortenedUrl::where('short_code', $shortCode)->first();

        if (!$url) {
            return response()->json([
                'success' => false,
                'error_code' => 1002,
                'message' => 'Short URL not found.'
            ], 200);
        }

        return redirect($url->original_url);
    }

    private function mapValidationError($field, $message)
    {
        $map = [
            'original_url.required' => 2001,
            'original_url.url' => 2002,
            'original_url.max' => 2003,
        ];

        $key = $field . '.' . $this->shortenMessage($message);

        return $map[$key] ?? 2999;
    }

    private function shortenMessage($message)
    {
        if (strpos($message, 'required') !== false) return 'required';
        if (strpos($message, 'url') !== false) return 'url';
        if (strpos($message, 'max') !== false) return 'max';
        return 'unknown';
    }
}

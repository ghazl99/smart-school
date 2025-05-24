<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class ZoomService
{
    protected $baseUrl;
    protected $clientId;
    protected $clientSecret;
    protected $accountId;

    public function __construct()
    {
        $this->baseUrl = config('zoom.base_url');
        $this->clientId = config('zoom.client_id');
        $this->clientSecret = config('zoom.client_secret');
        $this->accountId = config('zoom.account_id');
    }

    public function getAccessToken()
    {
        return Cache::remember('zoom_access_token', 3500, function () {
            $response = Http::asForm()
    ->withBasicAuth($this->clientId, $this->clientSecret)
    ->post('https://zoom.us/oauth/token', [
        'grant_type' => 'account_credentials',
        'account_id' => $this->accountId,
    ]);

            if ($response->failed()) {
                throw new \Exception('Failed to get access token from Zoom: ' . $response->body());
            }

            return $response->json()['access_token'];
        });
    }

    public function createMeeting(array $data)
    {
        $accessToken = $this->getAccessToken();

        $response = Http::withToken($accessToken)->post($this->baseUrl . 'users/me/meetings', [
            'topic' => $data['topic'],
            'type' => 2, // Scheduled meeting
            'start_time' => $data['start_time'], // format: 2025-05-17T10:00:00Z
            'duration' => $data['duration'],
            'timezone' => $data['timezone'] ?? 'Asia/Damascus',
            'password' => $data['password'] ?? null,
            'settings' => $data['settings'] ?? [
                'host_video' => true,
                'participant_video' => true,
                'waiting_room' => true,
                'join_before_host' => false,
                'mute_upon_entry' => true,
                'approval_type' => 0,
                'audio' => 'both',
                'auto_recording' => 'local',
            ],
        ]);

        if ($response->failed()) {
            throw new \Exception('Failed to create Zoom meeting: ' . $response->body());
        }

        return $response->json();
    }

}

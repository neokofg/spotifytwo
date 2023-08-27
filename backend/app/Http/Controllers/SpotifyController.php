<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SpotifyController extends Controller
{
    public function __construct()
    {
        $this->client_id = env('SPOTIFY_CLIENT_ID');
        $this->client_secret = env('SPOTIFY_CLIENT_SECRET');
    }
    public function getToken()
    {
        $response = Http::withBody("grant_type=client_credentials&client_id={$this->client_id}&client_secret={$this->client_secret}", "application/x-www-form-urlencoded")
            ->post('https://accounts.spotify.com/api/token');
        return $response->json();
    }

    public function search(Request $request)
    {
        $query = "?q=". $request['query'] ."&type=track&limit=1";
        $response = Http::withHeader("Authorization", "Bearer " . $request['token'])
            ->get("https://api.spotify.com/v1/search" . $query);
        return $response->json();
    }
}

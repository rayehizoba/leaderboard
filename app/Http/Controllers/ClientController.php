<?php

namespace App\Http\Controllers;

use App\Models\Leaderboard;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function show($slug)
    {
        $client = Leaderboard::where('slug', $slug)->firstOrFail();
        $leaders = $client->leaders()->orderBy('sort')->get();
        return view('leaderboard', compact('client', 'leaders'));
    }
}

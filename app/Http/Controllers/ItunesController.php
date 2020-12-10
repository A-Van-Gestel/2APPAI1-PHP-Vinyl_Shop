<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Json;

class ItunesController extends Controller
{
    public function index(Request $request)
    {
        $rss_feed = file_get_contents('https://rss.itunes.apple.com/api/v1/be/apple-music/top-songs/all/12/explicit.json');
        $rss_feed = json_decode($rss_feed);



        // Send to view
        $result = compact('rss_feed');
        Json::dump($result);                    // open http://vinyl_shop.test/itunes?json
        return view('shop.itunes', $result);     // add $result as second parameter
    }
}

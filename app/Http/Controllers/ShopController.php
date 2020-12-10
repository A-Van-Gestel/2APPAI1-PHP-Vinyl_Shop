<?php

namespace App\Http\Controllers;

use App\Genre;
use App\Record;
use Http;
use Illuminate\Http\Request;
use Json;

class ShopController extends Controller
{
    // Master Page: http://vinyl_shop.test/shop or http://localhost:3000/shop
    public function index(Request $request)
    {
        $genre_id = $request->input('genre_id') ?? '%'; //OR $genre_id = $request->genre_id ?? '%';
        $artist_title = '%' . $request->input('artist') . '%'; // OR $artist_title = '%' . $request->artist . '%';
        // Shorter version (with null coalescing operator)
        $records = Record::with('genre')
            ->where(function ($query) use ($artist_title, $genre_id) {
                $query->where('artist', 'like', $artist_title)
                    ->where('genre_id', 'like', $genre_id);
            })
            ->orWhere(function ($query) use ($artist_title, $genre_id) {
                $query->where('title', 'like', $artist_title)
                    ->where('genre_id', 'like', $genre_id);
            })
            ->orderBy('artist')
            ->paginate(12)
            ->appends(['artist'=> $request->input('artist'), 'genre_id' => $request->input('genre_id')]);   // OR ->appends(['artist' => $request->artist, 'genre_id' => $request->genre_id]);
        foreach ($records as $record) {
            $record->cover = $record->cover ?? "https://coverartarchive.org/release/$record->title_mbid/front-250.jpg";
        }

        // Make genre list for Filter
        $genres = Genre::orderBy('name')            // short version of orderBy('name', 'asc')
        ->has('records')        // only genres that have one or more records
        ->withCount('records')  // add a new property 'records_count' to the Genre models/objects
        ->get()
        ->transform(function ($item, $key) {
            // Set first letter of name to uppercase and add the counter in new attribute
            $item->name_and_counter = ucfirst($item->name) . ' (' . $item->records_count . ')';
            // Set first letter of name to uppercase
            $item->name = ucfirst($item->name);
            // Remove all fields that you don't use inside the view
            unset($item->created_at, $item->updated_at, $item->records_count);
            return $item;
        });

        // Send to view
        $result = compact('genres', 'records');     // $result = ['genres' => $genres, 'records' => $records]
        Json::dump($result);                    // open http://vinyl_shop.test/shop?json
        return view('shop.index', $result);     // add $result as second parameter
    }


    public function alt()
    {
        // Make genre list
        $genres = Genre::orderBy('name')            // short version of orderBy('name', 'asc')
        ->has('records')        // only genres that have one or more records
        ->withCount('records')  // add a new property 'records_count' to the Genre models/objects
        ->get()
            ->transform(function ($item) {
                $item->name = ucfirst($item->name);            // Set first letter of name to uppercase and add the counter
                unset($item->created_at, $item->updated_at, $item->records_count);            // Remove all fields that you don't use inside the view
                return $item;
            });

        // Make record list
        $records = Record::with('genre')
            ->orderBy('artist')
            ->get()
            ->transform(function ($item) {
                unset($item->created_at, $item->updated_at, $item->title_mbid, $item->cover);            // Remove all fields that you don't use inside the view
                return $item;
                }
            );

        // Send to page
        $result = compact('genres', 'records');     // $result = ['genres' => $genres, 'records' => $records]
        Json::dump($result);                    // open http://vinyl_shop.test/shop_alt?json
        return view('shop.alt', $result);     // add $result as second parameter
    }

    // Detail Page: http://vinyl_shop.test/shop/{id} or http://localhost:3000/shop/{id}
    public function show($id)
    {
        // Make get record by ID
        $record = Record::with('genre')->findOrFail($id);
        //dd($record);
        // Real path to cover image
        $record->cover = $record->cover ?? "https://coverartarchive.org/release/$record->title_mbid/front-250.jpg";
        // Combine artist + title
        $record->title = $record->artist . ' - ' . $record->title;
        // Links to MusicBrainz API (used by jQuery)
        // https://wiki.musicbrainz.org/Development/JSON_Web_Service
        $record->recordUrl = 'https://musicbrainz.org/ws/2/release/' . $record->title_mbid . '?inc=recordings+url-rels&fmt=json';
        // If stock > 0: button is green, otherwise the button is red
        $record->btnClass = $record->stock > 0 ? 'btn-outline-success' : 'btn-outline-danger';
        // You can't overwrite the attribute genre (object) with a string, so we make a new attribute
        $record->genreName = $record->genre->name;
        // Remove attributes you don't need for the view
        unset($record->genre_id, $record->artist, $record->created_at, $record->updated_at, $record->title_mbid, $record->genre);

        // get record info and convert it to json
        $response = Http::get($record->recordUrl)->json();
        $tracks = $response['media'][0]['tracks'];
        $tracks = collect($tracks)
            ->transform(function ($item, $key) {
                $item['length'] = gmdate('i:s', $item['length']/1000);      // PHP works with sec, not ms!!!
                unset($item['id'], $item['recording'], $item['number']);
                return $item;
            });
        // dd($tracks);

        // Send to page
        $result = compact('tracks', 'record');
        Json::dump($result);                    // open http://vinyl_shop.test/shop/{{ ID }}?json
        return view('shop.show', $result);  // Pass $result to the view
    }
}

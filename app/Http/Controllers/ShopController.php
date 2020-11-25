<?php

namespace App\Http\Controllers;

use App\Genre;
use App\Record;
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
        return view('shop.show', ['id' => $id]);  // Send $id to the view
    }
}

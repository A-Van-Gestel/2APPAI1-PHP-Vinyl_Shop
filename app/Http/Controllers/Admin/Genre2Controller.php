<?php

namespace App\Http\Controllers\Admin;

use App\Genre;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Genre2Controller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function index()
    {
        return view('admin.genres2.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Routing\Redirector
     */
    public function create()
    {
        return redirect('admin/genres2');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'name' => 'required|min:3|unique:genres,name'
        ]);

        $genre = new Genre();
        $genre->name = $request->name;
        $genre->save();
        return response()->json([
            'type' => 'success',
            'text' => "The genre <b>$genre->name</b> has been added"
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Genre  $genre
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Routing\Redirector
     */
    public function show(Genre $genre)
    {
        return redirect('admin/genres2');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Genre  $genre
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Routing\Redirector
     */
    public function edit(Genre $genre)
    {
        return redirect('admin/genres2');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Genre  $genre
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Genre $genre)
    {
        $this->validate($request,[
            'name' => 'required|min:3|unique:genres,name,' . $genre->id
        ]);

        $genre->name = $request->name;
        $genre->save();
        return response()->json([
            'type' => 'success',
            'text' => "The genre <b>$genre->name</b> has been updated"
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Genre  $genre
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Genre $genre)
    {
        $genre->delete();
        return response()->json([
            'type' => 'success',
            'text' => "The genre <b>$genre->name</b> has been deleted"
        ]);
    }


    // --- Custom Scripts ---
    public function qryGenres()
    {
        $genres = Genre::orderBy('name')
            ->withCount('records')
            ->get();
        return $genres;
    }
}

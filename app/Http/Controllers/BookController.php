<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookController extends Controller
{
    public function collections()
    {
        
        $series = DB::table('series')->select('id', 'name', 'resume', 'price')->get();

        
        $collections = $series->map(function ($serie) {
            $books = DB::table('books')
                ->select('id', 'title', 'subtitle', 'synopsis', 'price')
                ->where('series_id', $serie->id)
                ->get();

            return [
                'id' => $serie->id,
                'name' => $serie->name,
                'resume' => $serie->resume,
                'price' => $serie->price,
                'books' => $books
            ];
        });

        return response()->json([
            'status' => 'success',
            'collections' => $collections
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Resources\SalesResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Date;

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


    public function sales()
    {
        $sales = DB::table('sales as sal')
            ->leftJoin('books as boo', 'boo.id', '=', 'sal.book_id')
            ->leftJoin('series as ser', 'ser.id', '=', 'sal.serie_id')
            ->select(
                'sal.id',
                'sal.book_id',
                'sal.serie_id',
                'sal.sale_price',
                'sal.starts_at',
                'sal.ends_at',
                DB::raw('CASE 
                        WHEN sal.book_id IS NOT NULL THEN "book"
                        WHEN sal.serie_id IS NOT NULL THEN "serie"
                     END AS type'),
                DB::raw('boo.title AS book_title'),
                DB::raw('boo.subtitle AS book_subtitle'),
                DB::raw('boo.price AS book_price'),
                DB::raw('ser.name AS serie_name'),
                DB::raw('ser.price AS serie_price')
            )
            ->orderBy('ser.name', 'asc')
            ->orderBy('boo.title', 'asc')
            ->get();

        if ($sales->isEmpty()) {
            return response()->json([
                'status' => 'empty',
                'message' => 'Nenhuma promoção encontrada.'
            ], 404);
        }

        $sales_formatted = $sales->map(function ($sale) {
            return [
                'id' => $sale->id,
                'type' => $sale->type,
                'price' => $sale->type == 'book' ?  $sale->book_price :  $sale->serie_price,
                'sale_price' =>  $sale->sale_price,
                'book' => $sale->book_id ? [
                    'id' => $sale->book_id,
                    'title' => $sale->book_title,
                    'subtitle' => $sale->book_subtitle,
                ] : null,
                'serie' => $sale->serie_id ? [
                    'id' => $sale->serie_id,
                    'name' => $sale->serie_name,
                ] : null,
            ];
        });

        return response()->json([
            'status' => 'success',
            'sales' => $sales_formatted
        ]);
    }

    public function releases()
    {
        $releases = DB::table('books as boo')
            ->where('boo.is_release', 'T')
            ->select(
                'boo.id',
                'boo.title',
                'boo.subtitle',
                'boo.synopsis',
                'boo.author_id',
                'boo.category_id',
                'boo.series_id',
                'boo.is_release',
                'boo.price',
            )->get();

        if ($releases->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Não tem lançamentos'

            ]);
        }

        return response()->json([
            'status' => 'success',
            'releases' => $releases
        ]);
    }
}

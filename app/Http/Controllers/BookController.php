<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Book;
use App\Http\Resources\BookResource;
use App\Response\ApiResponseJson;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Exception;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $books = Book::all();
            return ApiResponseJson::ok(BookResource::collection($books)); // 🟢 List all books

        } catch (Exception $ex) {
            Log::error($ex);
            return ApiResponseJson::internalServerError(); // 🟡 Error
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'isbn' => 'required',
            'author' => 'required',
            'price' => 'required',
            'is_active' => 'required',
        ]);

        if ($validator->fails()) {
            return ApiResponseJson::unprocessableEntity($validator->errors()); // 🔴 Unprocessable entity
        }

        try {
            $book = Book::create($request->all());
            return ApiResponseJson::created(new BookResource($book)); // 🟢 Create a new book

        } catch (Exception $ex) {
            Log::error($ex);
            return ApiResponseJson::internalServerError(); // 🟡 Error
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $book = Book::find($id);
            if ($book === null) {
                return ApiResponseJson::notFound(); // 🔴 Not found
            }

            return ApiResponseJson::ok(new BookResource($book)); // 🟢 Show a single book

        } catch (Exception $ex) {
            Log::error($ex);
            return ApiResponseJson::internalServerError(); // 🔴 Error
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'isbn' => 'required',
            'author' => 'required',
            'price' => 'required',
            'is_active' => 'required',
        ]);

        if ($validator->fails()) {
            return ApiResponseJson::unprocessableEntity($validator->errors()); // 🔴 Unprocessable entity
        }

        try {
            $book = Book::find($id);
            if ($book === null) {
                return ApiResponseJson::notFound(); // 🔴 Not found
            }

            $book->update($request->all());
            return ApiResponseJson::ok(new BookResource($book)); // 🟢 Update a book

        } catch (Exception $ex) {
            Log::error($ex);
            return ApiResponseJson::internalServerError(); // 🔴 Error
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $book = Book::find($id);
            if ($book === null) {
                return ApiResponseJson::notFound(); // 🔴 Not found
            }

            $book->delete();
            return ApiResponseJson::noContent(); // 🟢 Delete a book

        } catch (Exception $ex) {
            Log::error($ex);
            return ApiResponseJson::internalServerError(); // 🔴 Error
        }
    }
}

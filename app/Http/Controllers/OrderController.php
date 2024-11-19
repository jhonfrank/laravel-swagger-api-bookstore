<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Order;
use App\Http\Resources\OrderResource;
use App\Response\ApiResponseJson;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Exception;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $orders = Order::all();
            return ApiResponseJson::ok(OrderResource::collection($orders)); // 🟢 List all orders

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
            'number' => 'required',
            'total' => 'required',
            'user_id' => 'required',
        ]);

        if ($validator->fails()) {
            return ApiResponseJson::unprocessableEntity($validator->errors()); // 🔴 Unprocessable entity
        }

        try {
            $order = Order::create($request->all());
            return ApiResponseJson::created(new OrderResource($order)); // 🟢 Create a new order

        } catch (Exception $ex) {
            Log::error($ex);
            return ApiResponseJson::internalServerError(); // 🔴 Error
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $order = Order::find($id);
            if ($order === null) {
                return ApiResponseJson::notFound(); // 🔴 Not found
            }

            return ApiResponseJson::ok(new OrderResource($order)); // 🟢 Show a single order

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
            'number' => 'required',
            'total' => 'required',
            'user_id' => 'required',
        ]);

        if ($validator->fails()) {
            return ApiResponseJson::unprocessableEntity($validator->errors()); // 🔴 Unprocessable entity
        }

        try {
            $order = Order::find($id);
            if ($order === null) {
                return ApiResponseJson::notFound(); // 🔴 Not found
            }

            $order->update($request->all());
            return ApiResponseJson::ok(new OrderResource($order)); // 🟢 Update a order

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
            $order = Order::find($id);
            if ($order === null) {
                return ApiResponseJson::notFound(); // 🔴 Not found
            }

            $order->delete();
            return ApiResponseJson::noContent(); // 🟢 Delete a order

        } catch (Exception $ex) {
            Log::error($ex);
            return ApiResponseJson::internalServerError(); // 🔴 Error
        }
    }
}

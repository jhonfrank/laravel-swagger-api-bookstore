<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Order;
use App\Http\Resources\OrderDetailResource;
use App\Response\ApiResponseJson;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Exception;

class OrderDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(string $orderId)
    {
        try {
            $order = Order::find($orderId);
            if ($order === null) {
                return ApiResponseJson::notFound(); // 🔴 Not found
            }

            $details = $order->details;
            return ApiResponseJson::ok(OrderDetailResource::collection($details)); // 🟢 List details for order

        } catch (Exception $ex) {
            Log::error($ex);
            return ApiResponseJson::internalServerError(); // 🔴 Error
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, string $orderId)
    {
        $validator = Validator::make($request->all(), [
            'unit_price' => 'required',
            'quantity' => 'required',
            'sub_total' => 'required',
            'order_id' => 'required',
            'book_id' => 'required',
        ]);

        if ($validator->fails()) {
            return ApiResponseJson::unprocessableEntity($validator->errors()); // 🔴 Unprocessable entity
        }

        try {
            $order = Order::find($orderId);
            if ($order === null) {
                return ApiResponseJson::notFound(); // 🔴 Not found
            }

            $detail = $order->details->create($request->all());
            return ApiResponseJson::created(new OrderDetailResource($detail)); // 🟢 Create a new detail for order

        } catch (Exception $ex) {
            Log::error($ex);
            return ApiResponseJson::internalServerError(); // 🔴 Error
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $orderId, string $detailId)
    {
        try {
            $order = Order::find($orderId);
            if ($order === null) {
                return ApiResponseJson::notFound(); // 🔴 Not found
            }

            $detail = $order->details->find($detailId);
            if ($detail === null) {
                return ApiResponseJson::notFound(); // 🔴 Not found
            }

            return ApiResponseJson::ok(new OrderDetailResource($detail)); // 🟢 Show a single detail for order

        } catch (Exception $ex) {
            Log::error($ex);
            return ApiResponseJson::internalServerError(); // 🔴 Error
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $orderId, string $detailId)
    {
        $validator = Validator::make($request->all(), [
            'unit_price' => 'required',
            'quantity' => 'required',
            'sub_total' => 'required',
            'order_id' => 'required',
            'book_id' => 'required',
        ]);

        if ($validator->fails()) {
            return ApiResponseJson::unprocessableEntity($validator->errors())->send(); // 🔴 Unprocessable entity
        }

        try {
            $order = Order::find($orderId);
            if ($order === null) {
                return ApiResponseJson::notFound(); // 🔴 Not found
            }

            $detail = $order->details->find($detailId);
            if ($detail === null) {
                return ApiResponseJson::notFound(); // 🔴 Not found
            }

            $detail->update($request->all());
            return ApiResponseJson::ok(new OrderDetailResource($detail))->send(); // 🟢 Update a detail for order

        } catch (Exception $ex) {
            Log::error($ex);
            return ApiResponseJson::internalServerError(); // 🔴 Error
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $orderId, string $detailId)
    {
        try {
            $order = Order::find($orderId);
            if ($order === null) {
                return ApiResponseJson::notFound()->send(); // 🔴 Not found
            }

            $detail = $order->details->find($detailId);
            if ($detail === null) {
                return ApiResponseJson::notFound()->send(); // 🔴 Not found
            }

            $detail->delete();
            return ApiResponseJson::noContent()->send(); // 🟢 Delete a order

        } catch (Exception $ex) {
            Log::error($ex);
            return ApiResponseJson::internalServerError()->send(); // 🔴 Error
        }
    }
}

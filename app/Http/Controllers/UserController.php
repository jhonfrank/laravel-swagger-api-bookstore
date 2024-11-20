<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Response\ApiResponseJson;
use App\Http\Resources\UserResource;
use Exception;

class UserController extends Controller
{
    /**
     * Register a new user.
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|confirmed',
        ]);

        if ($validator->fails()) {
            return ApiResponseJson::unprocessableEntity($validator->errors()); // 🔴 Unprocessable entity
        }

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            return ApiResponseJson::created(new UserResource($user), 'User successfully registered.'); // 🟢 Create a new user

        } catch (Exception $ex) {
            Log::error($ex);
            return ApiResponseJson::internalServerError(); // 🔴 Error
        }

    }

    /**
     * Generate a token.
     */
    public function generateToken(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return ApiResponseJson::unprocessableEntity($validator->errors()); // 🔴 Unprocessable entity
        }

        try {
            $user = User::where('email', $request->email)->first();

            if ($user === null) {
                return ApiResponseJson::unauthorized('Incorrect credentials'); // 🔴 Unauthorized
            }

            if (!Hash::check($request->password, $user->password)) {
                return ApiResponseJson::unauthorized('Incorrect credentials'); // 🔴 Unauthorized
            }

            if ($user->tokens->isNotEmpty()) {
                return ApiResponseJson::badRequest('User already has an active token.'); // 🔴 Bad request
            }

            if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                $user = Auth::user();

                $token = $user->createToken($user->email)->plainTextToken;

                $data = [
                    'user' => new UserResource($user),
                    'token' => $token,
                ];

                return ApiResponseJson::ok($data, 'Token generated successfully.'); // 🟢 Login successful
            }

            return ApiResponseJson::unauthorized('The token could not be generated.'); // 🔴 Unauthorized

        } catch (Exception $ex) {
            Log::error($ex);
            return ApiResponseJson::internalServerError(); // 🔴 Error
        }
    }

    /**
     * Revoke token.
     */
    public function revokeToken(Request $request)
    {
        try {
            $user = Auth::user();

            $user->tokens->each(function ($token) {
                $token->delete();
            });

            Auth::logout();

            return ApiResponseJson::noContent('Token has been deleted.'); // 🟢 Logout successful

        } catch (Exception $ex) {
            Log::error($ex);
            return ApiResponseJson::internalServerError(); // 🔴 Error
        }
    }


    /**
     * Revoke all tokens.
     */
    public function revokeAllTokens(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return ApiResponseJson::unprocessableEntity($validator->errors()); // 🔴 Unprocessable entity
        }

        try {
            $user = User::where('email', $request->email)->first();

            if ($user === null) {
                return ApiResponseJson::unauthorized('Incorrect credentials'); // 🔴 Unauthorized
            }

            if (!Hash::check($request->password, $user->password)) {
                return ApiResponseJson::unauthorized('Incorrect credentials'); // 🔴 Unauthorized
            }

            if ($user->tokens->isNotEmpty()) {
                $user->tokens->each(function ($token) {
                    $token->delete();
                });
            }

            $user->tokens->each(function ($token) {
                $token->delete();
            });

            return ApiResponseJson::ok(new UserResource($user), message: 'All tokens have been deleted.'); // 🟢 Deleted tokens

        } catch (Exception $ex) {
            Log::error($ex);
            return ApiResponseJson::internalServerError(); // 🔴 Error
        }
    }

    /**
     * Get user info.
     */
    public function userInfo(Request $request)
    {
        try {
            $user = Auth::user();
            return ApiResponseJson::ok(new UserResource($user)); // 🟢 Return user

        } catch (Exception $ex) {
            Log::error($ex);
            return ApiResponseJson::internalServerError(); // 🔴 Error
        }
    }
}

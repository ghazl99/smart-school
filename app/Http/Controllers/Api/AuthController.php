<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Services\ImageService;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRequest;
use App\Http\Resources\authResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function validate()
    {
        $user = Auth::user();

        return ApiResponse::success([
            'name' => $user->name,
            'email' => $user->email,
            'profile_photo_url' => $user->profile_photo_url,
        ]);
    }

    public function login(Request $request)
    {
        if (!$request->email) {
            $authed = Auth::attempt(['name' => $request->name, 'password' => $request->password]);
        } else {
            $authed = Auth::attempt(['email' => $request->email, 'password' => $request->password]);
        }
        if ($authed) {
            $user = Auth::user();
            $user->token = $authed;

            $user->save();

            return ApiResponse::success(authResource::make($user), 200);
        } else {
            return ApiResponse::error(419);
        }
    }

    public function updateProfile(AuthRequest $request)
    {
        // Validate incoming request data
        $validatedData = $request->validated();

        // Retrieve the authenticated user
        $user = Auth::user();

        // Update basic user information if provided
        $user->fill(array_filter([
            'name' => $validatedData['name'] ?? null,
            'email' => $validatedData['email'] ?? null,
            'phone' => $validatedData['phone'] ?? null,
        ]));

        // Save the updated user data
        $user->save();
        // Handle profile image upload if exist
        if ($request->hasFile('image')) {
            $imageService = new ImageService();
            $imageService->storeImage($user, $request->file('image'), 'users');

            // Refresh the user model to get updated data from DB (especially image path)
            $user->refresh();
        }

        // Return a successful response with updated user resource
        return ApiResponse::success(authResource::make($user), 200);
    }

    public function changePassword(Request $request)
    {
        try {
            // Validate incoming request
            $request->validate([
                'current_password' => 'required|string|min:8', // Ensure the current password is provided and valid
                'new_password' => 'required|string|min:8|confirmed', // Ensure the new password is provided and confirmed
            ]);

            $user = Auth::user(); // Get the currently authenticated user

            // Check if the current password matches the one in the database
            if (!Hash::check($request->current_password, $user->password)) {
                return ApiResponse::error(400, 'Current password is incorrect'); // Return error if current password is incorrect
            }

            // Update the password with the new password provided
            $user->password = Hash::make($request->new_password); // Hash the new password before saving
            $user->save(); // Save the updated user data to the database

            // Return a success response indicating that the password has been updated
            return ApiResponse::success('Password updated successfully', 200);
        } catch (\Exception $e) {
            // Return an error response if there was an issue during the password update
            return ApiResponse::error(500, 'An error occurred while updating password', $e->getMessage());
        }
    }


    public function logout(Request $request)
    {
        // Revoke the current JWT token
        JWTAuth::invalidate(JWTAuth::getToken());

        return ApiResponse::success(null, 200, 'Logged out successfully.');
    }
}

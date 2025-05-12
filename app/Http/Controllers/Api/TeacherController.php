<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Services\ImageService;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\TeacherRequest;
use App\Http\Resources\TeacherResource;
use App\Models\Teacher;
use App\Models\TeacherSection;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class TeacherController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(\Spatie\Permission\Middleware\RoleMiddleware::using('admin'), except: ['profilePersonal']),
        ];
    }

    public function index()
    {
        $teachers = Teacher::all();
        return ApiResponse::success(TeacherResource::collection($teachers), 200);
    }
    public function store(TeacherRequest $request)
    {
        DB::beginTransaction();
        try {

            $validatedData = $request->validated();
            $imageService = new ImageService();

            $user = new User();
            $user->name = $validatedData['name'];
            $user->email = $validatedData['email'];
            $user->password = Hash::make($validatedData['password']);
            $user->save();
            $token = Auth::attempt(['email' => $user->email, 'password' => $validatedData['password']]);
            $user = Auth::user();
            $user->token = $token;
            $user->save();
            $user->assignRole('teacher');
            if ($request->hasFile('image')) {
                $imageService->storeImage($user, $request->file('image'), 'users');
            }
            $teacher = new Teacher();
            $teacher->user_id = $user->id;
            $teacher->Specialization_id = $validatedData['Specialization_id'];
            $teacher->Gender_id = $validatedData['Gender_id'];
            $teacher->Joining_Date = $validatedData['Joining_Date'];
            $teacher->Address = $validatedData['Address'];
            $teacher->save();

            $teacher->sections()->sync($validatedData['sections']);
            DB::commit();
            return ApiResponse::success(TeacherResource::make($teacher), 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiResponse::error(419, $e->getMessage(), $e->getMessage());
        }
    }

    public function profilePersonal()
    {
        $user = Auth::user();
        if (!$user->hasRole('teacher')) {
            return ApiResponse::error(403,'Unauthorized. You must be a teacher.');
        }

        $teacher = Teacher::where('user_id', $user->id)->first();

        if (!$teacher) {
            return ApiResponse::error(404,'Teacher not found.');
        }
        return ApiResponse::success(TeacherResource::make($teacher), 200);
    }
}

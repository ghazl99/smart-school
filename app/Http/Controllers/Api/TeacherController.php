<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Teacher;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Models\AssignTeacher;
use App\Models\TeacherSection;
use App\Services\ImageService;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\TeacherRequest;
use App\Http\Resources\TeacherResource;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

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
        $teachers = Teacher::paginate(10);
        return ApiResponse::success(TeacherResource::collection($teachers), 200);
    }
    public function store(TeacherRequest $request)
    {
        DB::beginTransaction();
        try {
            // dd($request);
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

            $sectionIds = $request->sections;
            $subjectGroups = $request->subjects;
            foreach ($sectionIds as $index => $sectionId) {
                $subjectIds = $subjectGroups[$index];

                foreach ($subjectIds as $subjectId) {
                    AssignTeacher::create([
                        'teacher_id' => $teacher->id,
                        'section_id' => $sectionId,
                        'subject_id' => $subjectId,
                    ]);
                }
            }
            $teacher = Teacher::with('assignTeachers.section', 'assignTeachers.subject')->find($teacher->id);

            DB::commit();
            return ApiResponse::success(TeacherResource::make($teacher), 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiResponse::error(419, $e->getMessage(), $e->getMessage());
        }
    }

    public function show(Teacher $teacher){
        $teacher->load(['assignTeachers.section']);
        return ApiResponse::success(TeacherResource::make($teacher),200);
    }
    public function profilePersonal()
    {
        $user = Auth::user();
        if (!$user->hasRole('teacher')) {
            return ApiResponse::error(403, 'Unauthorized. You must be a teacher.');
        }

        $teacher = Teacher::where('user_id', $user->id)->first();
        $teacher->load(['assignTeachers.section']);
        if (!$teacher) {
            return ApiResponse::error(404, 'Teacher not found.');
        }
        return ApiResponse::success(TeacherResource::make($teacher), 200);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Quizze;
use App\Models\Section;
use App\Models\Student;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Models\AssignTeacher;
use App\Services\ImageService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\StudentRequest;
use App\Http\Resources\QuizzeResource;
use App\Http\Resources\StudentResource;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class StudentController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(\Spatie\Permission\Middleware\RoleMiddleware::using('admin'), except: ['profilePersonal', 'futureQuizze','topStudentsPerSection']),
        ];
    }

    public function index()
    {
        $students = Student::paginate(10);
        return ApiResponse::success(StudentResource::collection($students), 200);
    }

    public function store(StudentRequest $request)
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
            $user->assignRole('student');
            if ($request->hasFile('image')) {
                $imageService->storeImage($user, $request->file('image'), 'users');
            }
            $student = Student::create([
                'user_id' => $user->id,
                'gender_id' => $validatedData['gender_id'],
                'nationalitie_id' => $validatedData['nationalitie_id'],
                'blood_id' => $validatedData['blood_id'],
                'Date_Birth' => $validatedData['Date_Birth'],
                'Section_id' => $validatedData['Section_id'],
                'parent_id' => $validatedData['parent_id'],
                'academic_year' => $validatedData['academic_year']
            ]);
            DB::commit();
            return ApiResponse::success(StudentResource::make($student), 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiResponse::error(419, $e->getMessage(), $e->getMessage());
        }
    }

    public function profilePersonal()
    {
        $user = Auth::user();
        if (!$user->hasRole('student')) {
            return ApiResponse::error(403, 'Unauthorized. You must be a student.');
        }

        $student = Student::where('user_id', $user->id)->first();

        if (!$student) {
            return ApiResponse::error(404, 'Student not found.');
        }
        return ApiResponse::success(StudentResource::make($student), 200);
    }

    public function futureQuizze()
    {
        $user = Auth::user();

        $studentId = $user->student->id;
        $assignTeacher = AssignTeacher::where('section_id', $user->student->Section_id)->pluck('id');
        $quizze = Quizze::whereIn('assign_teacher_id', $assignTeacher)
            ->whereNotNull('max_score')
            ->whereDate('quiz_date', '>', Carbon::today())
            ->orderBy('quiz_date', 'desc')->paginate(10);
        return ApiResponse::success(QuizzeResource::collection($quizze), 200);
    }

    public function topStudentsPerSection()
    {
        $sections = Section::with(['students' => function ($query) {
            $query->withSum('marks as total_score', 'score')
                ->orderByDesc('total_score');
        }])->get();

        $result = $sections->map(function ($section) {
            $topStudents = $section->students->take(3)->map(function ($student) {
                return [
                    'student_id'   => $student->id,
                    'student_name' => $student->user->name ?? 'N/A',
                    'total_score'  => $student->total_score ?? 0,
                ];
            });

            return [
                'section_id'   => $section->id,
                'section_name' => $section->Name,
                'top_students' => $topStudents,
            ];
        });

        return ApiResponse::success($result, 200);
    }
}

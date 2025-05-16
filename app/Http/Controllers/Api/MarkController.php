<?php

namespace App\Http\Controllers\Api;

use App\Models\Mark;
use App\Models\Quizze;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Models\AssignTeacher;
use App\Http\Requests\MarkRequest;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\MarkResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\QuizzeResource;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class MarkController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('role:admin|teacher', only: ['store']),
        ];
    }
    public function store(MarkRequest $request)
    {
        DB::beginTransaction();
        try {
            $validatedData = $request->validated();
            $quizId = $request->quizze_id;
            $studentIds = $request->student_id;
            $scores = $request->score;
            foreach ($studentIds as $index => $studentId) {
                $marks[] = Mark::create([
                    'student_id' => $studentId,
                    'quizze_id'  => $quizId,
                    'score'      => $scores[$index],
                ]);
            }
            DB::commit();
            return ApiResponse::success(MarkResource::collection($marks), 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiResponse::error(419, $e->getMessage(), $e->getMessage());
        }
    }

    public function index()
    {
        $user = Auth::user();

        if ($user->hasRole('student') && $user->student) {
            $assignTeacher = AssignTeacher::find($user->student->Section_id);
            $quizze = Quizze::with('marks')->where('assign_teacher_id', $assignTeacher)->whereNotNull('max_score')->get();
        } else if ($user->hasRole('teacher') && $user->teacher) {
            $assignTeachers = $user->teacher->assignTeachers->pluck('id')->unique();
            $quizze = Quizze::with('marks')->whereIn('id', $assignTeachers)->whereNotNull('max_score')->paginate(10);
        } else if ($user->hasRole('admin')) {
            $quizze = Quizze::with('marks')->whereNotNull('max_score')->paginate(10);
        }
        return ApiResponse::success(QuizzeResource::collection($quizze), 200);
    }
}

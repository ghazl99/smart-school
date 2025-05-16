<?php

namespace App\Http\Controllers\Api;

use App\Models\Quizze;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Models\AssignTeacher;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\QuizzeRequest;
use App\Http\Resources\MarkResource;
use App\Http\Resources\QuestionResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\QuizzeResource;
use App\Models\Question;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use App\Models\Role;
use App\Models\Section;

class QuizzeController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('role:admin|teacher', only: ['store']),
        ];
    }

    public function store(QuizzeRequest $request)
    {
        DB::beginTransaction();
        try {
            $user = Auth::user();

            $assignTeacher = AssignTeacher::where('subject_id', $request->subject_id)
                ->where('teacher_id', $user->hasRole('teacher') ? $user->teacher->id : $request->teacher_id)
                ->where('section_id', $request->section_id)
                ->first();

            if (!$assignTeacher) {
                return ApiResponse::error(404, 'Teacher is not assigned to this subject and section.');
            }

            $quizze = Quizze::create([
                'name'               => $request->name,
                'assign_teacher_id'  => $assignTeacher->id,
                'max_score'          => $request->max_score
            ]);
            if ($request->questions) {
                foreach ($request->questions as $question) {
                    Question::create([
                        'quizze_id'     => $quizze->id,
                        'title'         => $question['title'],
                        'answers'       => $question['answers'],
                        'right_answer'  => $question['right_answer'],
                        'score'         => $question['score']
                    ]);
                }
            }
            DB::commit();
            return ApiResponse::success(QuizzeResource::make($quizze), 200);
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
            $quizze = Quizze::with('questions')->where('assign_teacher_id', $assignTeacher)->whereNull('max_score')->get();
        } else if ($user->hasRole('teacher') && $user->teacher) {
            $assignTeachers = $user->teacher->assignTeachers->pluck('id')->unique();
            $quizze = Quizze::with('questions')->whereIn('id', $assignTeachers)->whereNull('max_score')->paginate(10);
        } else if ($user->hasRole('admin')) {
            $quizze = Quizze::with('questions')->whereNull('max_score')->paginate(10);
        }
        return ApiResponse::success(QuizzeResource::collection($quizze), 200);
    }

    public function show(Quizze $quizze)
    {
        $quizze->load(['marks', 'marks.student.user', 'assignTeacher.teacher.user', 'assignTeacher.subject', 'assignTeacher.section']);
        return ApiResponse::success(QuizzeResource::make($quizze), 200);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\ClassroomRequest;
use App\Http\Resources\ClassroomResource;
use App\Models\Classroom;
use Illuminate\Http\Request;

class ClassroomController extends Controller
{
    public function index()
    {

        $classrooms = Classroom::all();
        return ApiResponse::success(ClassroomResource::collection($classrooms), 200);
    }

    public function Store(ClassroomRequest $request)
    {
        try {
            $validatedData = $request->validated();
            $classroom = Classroom::create($validatedData);
            return ApiResponse::success(ClassroomResource::make($classroom), 200);
        } catch (\Exception $e) {
            return ApiResponse::error(419, $e->getMessage(), $e->getMessage());
        }
    }

    public function show(Classroom $classroom)
    {
        $classroom->load('sections');
        return ApiResponse::success(ClassroomResource::make($classroom), 200);
    }
}

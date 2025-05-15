<?php

namespace App\Http\Controllers\Api;

use App\Models\Section;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\SectionRequest;
use App\Http\Resources\SectionResource;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class SectionController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(\Spatie\Permission\Middleware\RoleMiddleware::using('admin'), except: ['index']),
        ];
    }
    public function store(SectionRequest $request)
    {
        try {
            $validatedData = $request->validated();
            $section = Section::create($validatedData);
            return ApiResponse::success(SectionResource::make($section), 200);
        } catch (\Exception $e) {
            return ApiResponse::error(419, $e->getMessage(), $e->getMessage());
        }
    }

    public function index()
    {
        $sections = Section::all();
        $sections->load(['classroom']);
        return ApiResponse::success(SectionResource::collection($sections), 200);
    }

    public function update(SectionRequest $request, Section $section)
    {
        try {
            $validatedData = $request->validated();
            $section->update($validatedData);
            return ApiResponse::success(SectionResource::make($section), 200);
        } catch (\Exception $e) {
            return ApiResponse::error(419, $e->getMessage(), $e->getMessage());
        }
    }

    public function show(Section $section)
    {
        $section->load(['assignTeachers.teacher.user']);
        return ApiResponse::success(SectionResource::make($section));
    }
}

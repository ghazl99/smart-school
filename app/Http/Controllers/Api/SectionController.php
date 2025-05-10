<?php

namespace App\Http\Controllers\Api;

use App\Models\Section;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\SectionRequest;
use App\Http\Resources\SectionResource;

class SectionController extends Controller
{
    public function store(SectionRequest $request){
        try {
            $validatedData = $request->validated();
            $section=Section::create($validatedData);
            return ApiResponse::success(SectionResource::make($section),200);
            } catch (\Exception $e) {
            return ApiResponse::error(419, $e->getMessage(), $e->getMessage());
        }
    }

    public function show(Section $section){
        return ApiResponse::success(SectionResource::make($section),200);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Models\Fee;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Requests\FeeRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\FeeResource;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class FeeController extends Controller implements HasMiddleware
{

     public static function middleware(): array
    {
        return [
            new Middleware(\Spatie\Permission\Middleware\RoleMiddleware::using('admin')),
        ];
    }
    
    public function store(FeeRequest $request){
        try {
            $validatedData = $request->validated();
            $fee = Fee::create($validatedData);
            return ApiResponse::success(FeeResource::make($fee), 200);
        } catch (\Exception $e) {
            return ApiResponse::error(419, $e->getMessage(), $e->getMessage());
        }
    }
}

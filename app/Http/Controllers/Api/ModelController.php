<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Requests\ModelRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\ModelResource;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
class ModelController extends Controller  implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(\Spatie\Permission\Middleware\RoleMiddleware::using('admin'), except: ['index']),
        ];
    }

    public function index($model)
    {
        $modelName = "App\\Models\\" . $model;
        $data = $modelName::paginate(10);
        return ApiResponse::success(ModelResource::collection($data), 200);
    }

    public function store(ModelRequest $request, $model)
    {
        try {
            $validatedData = $request->validated();
            $modelName = "App\\Models\\" . $model;
            $object = $modelName::create($validatedData);
            return ApiResponse::success(ModelResource::make($object), 200);
        } catch (\Exception $e) {
            return ApiResponse::error(419, $e->getMessage(), $e->getMessage());
        }
    }
    public function update(Request $request, $id)
    {
        try {

            $modelName = "App\\Models\\" . $request->model;
            $object = $modelName::find($id);

            if (!isset($object)) {
                $response = array(
                    'success' => false,
                    'msg' => 'خطأ في رقم السجل ',
                );
            } else {
                $object->update(['name' => $request->name]);
                $response = array(
                    'success' => true,
                    'msg' => 'تم التعديل بنجاح'
                );
            }
        } catch (\Exception $e) {
            $response = array(
                'success' => false,
                'msg' => $e->getMessage()
            );
        }
        return response()->json($response);
    }
}

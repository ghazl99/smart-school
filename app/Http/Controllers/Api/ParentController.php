<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\MyParent;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Services\ImageService;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\ParentRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\ParentResource;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class ParentController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(\Spatie\Permission\Middleware\RoleMiddleware::using('admin'), except: ['profilePersonal']),
        ];
    }

    public function index()
    {
        $parents = MyParent::paginate(10);
        return ApiResponse::success(ParentResource::collection($parents), 200);
    }
    public function store(ParentRequest $request)
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
            $user->assignRole('parent');

            $parent = MyParent::create([
                'user_id' => $user->id,
                'Name_Father' => $validatedData['Name_Father'],
                'National_ID_Father' => $validatedData['National_ID_Father'],
                'Passport_ID_Father' => $validatedData['Passport_ID_Father'],
                'Phone_Father' => $validatedData['Phone_Father'],
                'Job_Father' => $validatedData['Job_Father'],
                'Nationality_Father_id' => $validatedData['Nationality_Father_id'],
                'Blood_Type_Father_id' => $validatedData['Blood_Type_Father_id'],
                'Religion_Father_id' => $validatedData['Religion_Father_id'],
                'Address_Father' => $validatedData['Address_Father'],
                'Name_Mother' => $validatedData['Name_Mother'],
                'National_ID_Mother' => $validatedData['National_ID_Mother'],
                'Passport_ID_Mother' => $validatedData['Passport_ID_Mother'],
                'Phone_Mother' => $validatedData['Phone_Mother'],
                'Job_Mother' => $validatedData['Job_Mother'],
                'Nationality_Mother_id' => $validatedData['Nationality_Mother_id'],
                'Blood_Type_Mother_id' => $validatedData['Blood_Type_Mother_id'],
                'Religion_Mother_id' => $validatedData['Religion_Mother_id'],
                'Address_Mother' => $validatedData['Address_Mother']
            ]);

            if ($request->hasFile('image')) {
                $imageService->storeImage($user, $request->file('image'), 'users');
            }

            DB::commit();

            return ApiResponse::success(ParentResource::make($parent), 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiResponse::error(419, $e->getMessage(), $e->getMessage());
        }
    }
    public function profilePersonal()
    {
        $user = Auth::user();

        if (!$user->hasRole('parent')) {
            return ApiResponse::error( 403,'Unauthorized. You must have the parent role.');
        }

        $parent = MyParent::where('user_id', $user->id)->first();

        if (!$parent) {
            return ApiResponse::error( 404,'Parent data not found.');
        }
        return ApiResponse::success(ParentResource::make($parent), 200);
    }
}

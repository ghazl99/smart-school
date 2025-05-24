<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Models\OnlineClasses;
use App\Services\ZoomService;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\OnlineClassesRequest;
use App\Http\Resources\OnlineClassResource;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class OnlineClasseController extends Controller implements HasMiddleware
{

    public static function middleware(): array
    {
        return [
            new Middleware('role:admin|teacher'),
        ];
    }
    public function store(OnlineClassesRequest $request, ZoomService $zoom)
    {
        $validatedData = $request->validated();
        DB::beginTransaction();
        try {
            $meeting = $zoom->createMeeting($validatedData);
            onlineClasses::create([
                'integration' => true,
                'section_id' => $request->section_id,
                'user_id' => auth()->user()->id,
                'meeting_id' => $meeting['id'], // Meeting ID
                'topic' => $meeting['topic'],
                'start_at' => Carbon::parse($request->start_time)->toDateTimeString(), // ✅ تحويل التاريخ
                'duration' => $meeting['duration'],
                'password' => $meeting['password'], // Password
                'start_url' => $meeting['start_url'], // Start URL
                'join_url' => $meeting['join_url'], // رابط الاجتماع
            ]);
            DB::commit();
            return ApiResponse::success($meeting, 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiResponse::error(419, $e->getMessage(), $e->getMessage());
        }
    }

    public function index()
    {
        $online_classes = OnlineClasses::with([
            'user',
            'section.classroom.grade'
        ])->get();
        return ApiResponse::success(OnlineClassResource::collection($online_classes), 200);
    }
}

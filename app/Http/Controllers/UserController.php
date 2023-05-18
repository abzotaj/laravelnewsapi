<?php

namespace App\Http\Controllers;
use App\Helpers;
use App\Models\NewsSource;
use App\Models\UserNewsSource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function get(): JsonResponse
    {
        $user = Auth::user();
        Auth::authenticate();
        $newsSources = Helpers::getNewsSourcesForUser($user->id);
        $user->news_sources = $newsSources;

        return response()->json([
            'user' => $user
        ], 200);
    }

    public function logout(): JsonResponse
    {
        $user = Auth::user();
        $user->currentAccessToken()->delete();

        return response()->json([
            'message' => 'User logged out successfully'
        ], 200);
    }

    public function update(Request $request): JsonResponse
    {
        $user = Auth::user();

        $validateUserInput = Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required',
            'news_sources.*.source_name' => 'required|string|max:255',
            'news_sources.*.source_tag' => 'required|string|max:255',
        ]);


        if($validateUserInput->fails()){

            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validateUserInput->errors()
            ], 401);
        }

        UserNewsSource::where("user_id", $user->id)->delete();

        foreach($request->news_sources as $source){

            $existingNewsSource = NewsSource::where('source_tag',  $source['source_tag'])->first();
            $newsSource = new UserNewsSource();
            $newsSource->user()->associate($user);
            $newsSource->newsSource()->associate($existingNewsSource);
            $newsSource->save();
        }

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'updated_at' => now()
        ]);

        return response()->json([
            'message' => 'User logged out successfully'
        ], 200);
    }
}

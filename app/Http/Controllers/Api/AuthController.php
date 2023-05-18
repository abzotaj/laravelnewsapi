<?php

namespace App\Http\Controllers\Api;

use App\Helpers;
use App\Models\NewsSource;
use App\Models\User;
use App\Models\UserNewsSource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Create User
     * @param Request $request
     * @return JsonResponse
     */
    public function createUser(Request $request): JsonResponse
    {
        try {

            $validateUserInput = Validator::make($request->all(),
                [
                    'name' => 'required',
                    'email' => 'required|email|unique:users,email',
                    'password' => 'required',
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

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);

            foreach($request->news_sources as $source){

                $existingNewsSource = NewsSource::where('source_tag',  $source['source_tag'])->first();

                $newsSource = new UserNewsSource();
                $newsSource->user()->associate($user);
                $newsSource->newsSource()->associate($existingNewsSource);
                $newsSource->save();
            }

            return response()->json([
                'status' => true,
                'message' => 'User Created Successfully',
                'token' => $user->createToken("API_TOKEN")->plainTextToken,
                'user' => $user
            ], 200);

        } catch (\Throwable $th) {
            report($th);
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Login The User
     * @param Request $request
     * @return JsonResponse
     */
    public function loginUser(Request $request): JsonResponse
    {
        try {
            $validateUser = Validator::make($request->all(),
                [
                    'email' => 'required|email',
                    'password' => 'required'
                ]);

            if($validateUser->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            if(!Auth::attempt($request->only(['email', 'password']))){
                return response()->json([
                    'status' => false,
                    'message' => 'Email & Password does not match with our record.',
                ], 401);
            }

            $user = User::where('email', $request->email)->first();
            $newsSources = Helpers::getNewsSourcesForUser($user->id);
            $user->news_sources = $newsSources;

            return response()->json([
                'status' => true,
                'message' => 'User Logged In Successfully',
                'token' => $user->createToken("API_TOKEN")->plainTextToken,
                'user' => $user
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }


    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'User logged out successfully'
        ], 200);
    }
}

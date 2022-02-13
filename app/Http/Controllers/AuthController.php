<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    //
    public function login(Request $request)
    {
        $email = $request->email;
        $password = $request->password;

        // Check if the fields are empty
        if (empty($email) || empty($password)) {
            return response()->json(['status_code' => 400, 'status' => 'error', 'message' => 'Please fill in all the fields to continue.']);
        }

        $credentials = request(['email', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['status_code' => 401, 'status' => 'error', 'message' => 'Access token not provided'], 401);
        }

        return $this->respondWithToken($token);
    }

    public function register(Request $request)
    {
        // Get incoming form data
        $name = $request->name;
        $email = $request->email;
        $password = $request->password;

        // Check if all inputs are provided
        if (empty($name) || empty($email) || empty($password)) {
            return response()->json(['status_code' => 400, 'status' => 'error', 'message' => 'Make sure Name, email and password fields are filled']);
        }

        //Check if email provided is valid
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return response()->json(['status_code' => 400, 'status' => 'error', 'message' => 'Please provide a valid email address']);
        }

        // Check password length
        if (strlen($password) < 6) {
            return response()->json(['status_code' => 400, 'status' => 'error', 'message' => 'Password should be min 6 characters in length']);
        }

        // Check if password already exists
        if (User::where('email', '=', $email)->exists()) {
            return response()->json(['status_code' => 400, 'status' => 'error', 'message' => 'User already exists with this email']);
        }

        try {
            // Try and save user details in the database
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = app('hash')->make($request->password);
            $user->save();
            $lastInsertId = $user->id;

            //Get the inserted users data
            $user = User::findOrFail($lastInsertId);
            $userDetails = array();
            $userDetails['name'] = $user->name;
            $userDetails['email'] = $user->email;
            $userDetails['token_details'] = $this->login($request);

            return response()->json(['status_code' => 201, 'message' => 'Account creation successful', 'data' => $userDetails]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function logout()
    {
        auth()->logout();
        return response()->json(['status_code' => 200, 'status' => 'success', 'message' => 'Successfully logged out']);
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60 //31536000
        ]);
    }
}

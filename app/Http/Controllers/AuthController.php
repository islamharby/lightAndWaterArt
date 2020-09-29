<?php

namespace App\Http\Controllers;

use App\User;
use Auth;
use Hash;
use Illuminate\Http\Request;
use Socketlabs\Message\BasicMessage;
use Socketlabs\Message\EmailAddress;
use Socketlabs\SocketLabsClient;
use Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $email = $request->email;
        $password = $request->password;

        if ($token = Auth::attempt(['email' => $email, 'password' => $password])) {
            return $this->respondWithToken($token);
        }
        return response()->json(['error' => 'Email Or Password Not True'], 401);
    }
    public function logout()
    {
        $this->guard()->logout();
        return response()->json(['message' => 'success yo logout']);
    }
    public function sendEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()], 422);
        }
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json(['error' => 'Not Found This User'], 422);
        }
        $user->token = str_random(40);
        $user->save();
        $serverId = 29818;
        $injectionApiKey = "s8R7Tpf9B4Pgj6G5McZk";
        $client = new SocketLabsClient($serverId, $injectionApiKey);
        $message = new BasicMessage();
        $message->subject = 'Forget Password';

        $message->htmlBody = "<html>http://dashboard.light-water-art.com/#/authentication/reset-password?mail=" . $request->email . "&code=" . $user->token . "</html>";
        $message->from = new EmailAddress('noreply@light-water-art.com');

        $message->addToAddress($request->email);
        $response = $client->send($message);
        return response()->json(['data' => ' done to send email to this user']);
    }
    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'password' => 'required',
            'token' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()], 422);
        }
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json(['error' => 'Not Found This User'], 422);
        }
        if ($user->token != $request->token) {
            return response()->json(['error' => 'your password has already been reset'], 422);
        }
        $user->token = null;
        $user->password = Hash::make($request->password);
        $user->save();
        return response()->json(['data' => 'done']);
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $this->guard()->factory()->getTTL() * 1,
            'data' => $this->guard()->user(),
        ]);
    }
    public function guard()
    {
        return Auth::guard();
    }
}

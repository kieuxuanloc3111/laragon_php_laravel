<?php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use App\Events\MessageSent;
use Pusher\Pusher;
use Illuminate\Support\Facades\Broadcast;
Route::post('/register', function (Request $request) {

    $request->validate([
        'name' => 'required',
        'email' => 'required|email|unique:users',
        'password' => 'required|min:6',
        'avatar' => 'required|image',
    ]);

    $avatarPath = null;

    if ($request->hasFile('avatar')) {

        $avatarPath = $request->file('avatar')
            ->store('avatars', 'public');
    }

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => bcrypt($request->password),
        'avatar' => $avatarPath,
    ]);

    return response()->json([
        'message' => 'Register success',

        'user' => [
            ...$user->toArray(),

            'avatar_url' => asset('storage/' . $user->avatar),
        ]
    ]);
});

Route::post('/login', function (Request $request) {

    $user = User::where('email', $request->email)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {

        return response()->json([
            'message' => 'Invalid credentials'
        ], 401);
    }

    $token = $user->createToken('api-token')->plainTextToken;

    return response()->json([
        'token' => $token,

        'user' => [
            ...$user->toArray(),

            'avatar_url' => asset('storage/' . $user->avatar),
        ]
    ]);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {

    $user = $request->user();

    return response()->json([

        ...$user->toArray(),

        'avatar_url' => asset('storage/' . $user->avatar),
    ]);
});

Route::post('/pusher/auth', function (Request $request) {

    $user = $request->user();

    if (!$user) {

        return response()->json([
            'message' => 'Unauthenticated'
        ], 401);
    }

    $channelName = $request->channel_name;

    preg_match('/private-chat\.(\d+)/', $channelName, $matches);

    $channelUserId = $matches[1] ?? null;

    if ((int) $user->id !== (int) $channelUserId) {

        return response()->json([
            'message' => 'Forbidden'
        ], 403);
    }

    $pusher = new Pusher(
        config('broadcasting.connections.pusher.key'),
        config('broadcasting.connections.pusher.secret'),
        config('broadcasting.connections.pusher.app_id'),
        config('broadcasting.connections.pusher.options'),
    );

    return $pusher->authorizeChannel(
        $request->channel_name,
        $request->socket_id
    );

})->middleware('auth:sanctum');

Route::get('/send-test', function () {

$pusher = new Pusher(
    config('broadcasting.connections.pusher.key'),
    config('broadcasting.connections.pusher.secret'),
    config('broadcasting.connections.pusher.app_id'),
    config('broadcasting.connections.pusher.options'),
);

$pusher->trigger(
    'private-chat.2',
    'chat-message',
    [
        'message' => 'HELLO REALTIME'
    ]
);

return 'ok';

});
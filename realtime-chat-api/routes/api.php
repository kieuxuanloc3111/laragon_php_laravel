<?php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use App\Events\MessageSent;
use Pusher\Pusher;
use Illuminate\Support\Facades\Broadcast;
use App\Models\Message;
use App\Models\Group;
use App\Http\Controllers\GroupController;
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

    // Trả token luôn để frontend đăng nhập ngay sau khi đăng ký.
    $token = $user->createToken('api-token')->plainTextToken;

    return response()->json([
        'message' => 'Register success',

        'token' => $token,

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

// Danh sách user khác (để chọn người chat 1-1 hoặc thêm vào group).
Route::middleware('auth:sanctum')->get('/users', function (Request $request) {

    $users = User::where('id', '!=', $request->user()->id)
        ->orderBy('name')
        ->get();

    return response()->json($users);
});

// Group chat
Route::middleware('auth:sanctum')->group(function () {

    Route::get('/groups', [GroupController::class, 'index']);
    Route::post('/groups', [GroupController::class, 'store']);
    Route::get('/groups/{group}/messages', [GroupController::class, 'messages']);
    Route::post('/groups/{group}/messages', [GroupController::class, 'sendMessage']);
});

Route::post('/pusher/auth', function (Request $request) {

    $user = $request->user();

    if (!$user) {

        return response()->json([
            'message' => 'Unauthenticated'
        ], 401);
    }

    $channelName = $request->channel_name;

    // Kênh chat 1-1: private-chat.{userId} -> chỉ chính chủ được nghe.
    if (preg_match('/^private-chat\.(\d+)$/', $channelName, $matches)) {

        if ((int) $user->id !== (int) $matches[1]) {

            return response()->json([
                'message' => 'Forbidden'
            ], 403);
        }

    // Kênh group: private-group.{groupId} -> chỉ thành viên được nghe.
    } elseif (preg_match('/^private-group\.(\d+)$/', $channelName, $matches)) {

        $isMember = Group::where('id', $matches[1])
            ->whereHas('members', fn ($q) => $q->where('users.id', $user->id))
            ->exists();

        if (!$isMember) {

            return response()->json([
                'message' => 'Forbidden'
            ], 403);
        }

    } else {

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

Route::post('/messages', function (Request $request) {


$message = Message::create([

    'sender_id' => $request->user()->id,

    'receiver_id' => $request->receiver_id,

    'message' => $request->message,
]);

$message->load('sender');

broadcast(new MessageSent(
$message,
$request->receiver_id
));


return response()->json(

$message->load('sender')

);


})->middleware('auth:sanctum');

Route::get('/messages/{userId}', function (
Request $request,
$userId
) {


$messages = Message::where(function ($query)
    use ($request, $userId) {

    $query
        ->where(
            'sender_id',
            $request->user()->id
        )
        ->where(
            'receiver_id',
            $userId
        );
})
->orWhere(function ($query)
    use ($request, $userId) {

    $query
        ->where(
            'sender_id',
            $userId
        )
        ->where(
            'receiver_id',
            $request->user()->id
        );
})
->with('sender')
->orderBy('id')
->get();

return response()->json(
    $messages
);


})->middleware('auth:sanctum');

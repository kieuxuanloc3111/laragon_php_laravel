<?php

namespace App\Http\Controllers;

use App\Events\GroupMessageSent;
use App\Models\Group;
use App\Models\Message;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    /**
     * Danh sách group mà user hiện tại tham gia.
     */
    public function index(Request $request)
    {
        $groups = $request->user()
            ->groups()
            ->withCount('members')
            ->latest('groups.id')
            ->get();

        return response()->json($groups);
    }

    /**
     * Tạo group mới. Người tạo tự động là thành viên.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'member_ids' => 'array',
            'member_ids.*' => 'integer|exists:users,id',
        ]);

        $group = Group::create([
            'name' => $data['name'],
            'created_by' => $request->user()->id,
        ]);

        // Gộp người tạo + các thành viên được chọn (loại trùng).
        $memberIds = collect($data['member_ids'] ?? [])
            ->push($request->user()->id)
            ->unique()
            ->values()
            ->all();

        $group->members()->sync($memberIds);

        return response()->json(
            $group->load('members'),
            201
        );
    }

    /**
     * Lịch sử tin nhắn của group (chỉ thành viên xem được).
     */
    public function messages(Request $request, Group $group)
    {
        $this->authorizeMember($request, $group);

        $messages = $group->messages()
            ->with('sender')
            ->orderBy('id')
            ->get();

        return response()->json($messages);
    }

    /**
     * Gửi tin nhắn vào group và broadcast realtime cho cả nhóm.
     */
    public function sendMessage(Request $request, Group $group)
    {
        $this->authorizeMember($request, $group);

        $data = $request->validate([
            'message' => 'required|string',
        ]);

        $message = Message::create([
            'sender_id' => $request->user()->id,
            'group_id' => $group->id,
            'message' => $data['message'],
        ]);

        $message->load('sender');

        broadcast(new GroupMessageSent($message, $group->id));

        return response()->json($message);
    }

    /**
     * Chặn người không phải thành viên truy cập group.
     */
    private function authorizeMember(Request $request, Group $group): void
    {
        $isMember = $group->members()
            ->where('users.id', $request->user()->id)
            ->exists();

        abort_unless($isMember, 403, 'Bạn không thuộc nhóm này');
    }
}

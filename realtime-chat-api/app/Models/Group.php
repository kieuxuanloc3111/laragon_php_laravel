<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $fillable = [
        'name',
        'avatar',
        'created_by',
    ];

    protected $appends = [
        'avatar_url',
    ];

    public function members()
    {
        return $this->belongsToMany(User::class, 'group_user')
            ->withTimestamps();
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function messages()
    {
        return $this->hasMany(Message::class, 'group_id');
    }

    public function getAvatarUrlAttribute()
    {
        return $this->avatar
            ? asset('storage/' . $this->avatar)
            : null;
    }
}

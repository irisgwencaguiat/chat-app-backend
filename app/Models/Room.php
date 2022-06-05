<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function roomMembers()
    {
        return $this->hasMany(RoomMember::class);
    }

    public function lastChat()
    {
        return $this->hasOne(Chat::class)->orderBy("created_at", "DESC");
    }
}

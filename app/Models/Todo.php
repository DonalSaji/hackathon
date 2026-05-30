<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function users()
    {
        return $this->belongsTo(User::class);
    }

    public function recurrence()
    {
        return $this->hasOne(Recurrence::class, 'task_id', 'id');
    }
}

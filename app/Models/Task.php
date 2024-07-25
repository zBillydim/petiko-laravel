<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Task extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['user_id', 'task_title', 'task_description', 'due_date', 'status', 'priority'];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
    protected $casts = [
        'due_date' => 'date',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function getDueDateAttribute($value)
    {
        return Carbon::parse($value)->format('d/m/Y');
    }
    public function setDueDateAttribute($value)
    {
        $this->attributes['due_date'] = Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d');
    }
}

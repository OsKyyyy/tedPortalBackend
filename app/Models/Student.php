<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Student extends Model
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $fillable = [
        'name', 'surname', 'phone', 'email', 'student_no','branch_id'
    ];
}

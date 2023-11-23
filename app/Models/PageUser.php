<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PageUser extends Model
{
    use HasFactory;

    protected $table      = 'page_user';
    protected $fillable   = ['page_id', 'user_id', 'event_id'];
    public    $timestamps = false;
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @author Douglas Vicentini (douglas.dvferreira@gmail.com)
 */
class PresentationAward extends Model
{
    use HasFactory;

    protected $connection = 'brain_challenge';
    protected $table      = 'presentation_awards';
    protected $fillable   = ['event_id', 'presentation_id', 'user_id'];
    public    $timestamps = false;
}

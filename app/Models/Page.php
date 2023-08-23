<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @author Douglas Vicentini (douglas.dvferreira@gmail.com)
 */
class Page extends Model
{
    use HasFactory;

    protected $connection = 'brain_challenge';

    protected $fillable = ['name', 'url', 'status'];
}

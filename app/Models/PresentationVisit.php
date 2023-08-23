<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @author Douglas Vicentini (douglas.dvferreira@gmail.com)
 */
class PresentationVisit extends Model
{
    use HasFactory;

    protected $connection = 'brain_challenge';
    protected $table      = 'presentation_visits';
}

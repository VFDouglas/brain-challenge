<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @author Douglas Vicentini (douglas.dvferreira@gmail.com)
 */
class Parameter extends Model
{
    use HasFactory;

    protected $connection = 'brain_challenge';
    protected $table      = 'parameters';

    public static function getParameter($name): array
    {
        return self::query()
            ->select([
                'value'
            ])
            ->where('name', '=', $name)
            ->get()
            ->toArray();
    }
}

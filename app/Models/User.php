<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @author Douglas Vicentini (douglas.dvferreira@gmail.com)
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $connection = 'brain_challenge';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'status'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Checks the user acceptance terms
     * @author Douglas Vicentini (douglas.dvferreira@gmail.com)
     */
    public static function acceptTerms(): array
    {
        $response = [];
        try {
            $user = self::query()->find(session('event_access.user_id'));

            $user->accepted_terms = 1;
            if ($user->save()) {
                $response['msg'] = 'success';
                session(['event_access.accepted_terms' => 1]);
            } else {
                throw new Exception();
            }
        } catch (Exception $e) {
            $response['msg'] = $e->getMessage();
        }
        return $response;
    }
}

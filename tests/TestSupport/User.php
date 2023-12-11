<?php

namespace MagicLink\Test\TestSupport;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Support\Str;
use Orbit\Concerns\Orbital;

/**
 * @property string $email
 */
class User extends Model implements AuthorizableContract, AuthenticatableContract
{
    use Authorizable, Authenticatable, Orbital;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['email'];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = Str::uuid();
        });
    }

    public static function schema(Blueprint $table)
    {
        $table->uuid('id')->primary();
        $table->string('email');
    }

    public function getIncrementing()
    {
        return false;
    }
}

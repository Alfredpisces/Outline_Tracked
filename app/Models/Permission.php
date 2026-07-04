<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'module_name',
        'action_name',
    ];

    /**
     * Get the individual public school personnel linked to this modular permission.
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_permissions')
                    ->withPivot('granted_at')
                    ->withTimestamps();
    }

    /**
     * Get the roles that include this permission.
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'permission_role')
                    ->withTimestamps();
    }
}

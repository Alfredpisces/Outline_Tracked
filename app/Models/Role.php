<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['name'];

    /**
     * Get the users that belong to this role.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'role_user');
    }

    /**
     * Get the permissions that belong to this role.
     */
    public function permissions(): BelongsToMany
    {
        // Make sure to import App\Models\Permission at the top if you aren't using the fully qualified namespace
        return $this->belongsToMany(Permission::class, 'permission_role');
    }
}

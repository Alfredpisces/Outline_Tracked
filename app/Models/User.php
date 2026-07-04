<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use App\Models\Role;
use App\Models\Permission;
use App\Models\UserPermission;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'employee_id',
        'first_name',
        'last_name',
        'email',
        'password',
        'current_tier',
        'is_active',
        'last_login_ip',
        'last_login_at',
        'suspicious_activity',
        'email_verified_at',
        'deped_rank',
        'department',
        'is_verified',
        'verified_by',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_user')->withTimestamps();
    }

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'user_permissions')
            ->using(UserPermission::class)
            ->withPivot('granted_at')
            ->withTimestamps();
    }

    public function authoredDailyLessonLogs(): HasMany
    {
        return $this->hasMany(DailyLessonLog::class, 'author_id');
    }

    public function reviewedDailyLessonLogs(): HasMany
    {
        return $this->hasMany(DailyLessonLog::class, 'reviewer_id');
    }

    public function cotRatings(): HasMany
    {
        return $this->hasMany(CotRating::class, 'teacher_id');
    }

    public function professionalGrowthEntries(): HasMany
    {
        return $this->hasMany(ProfessionalGrowth::class, 'teacher_id');
    }

    public function incidentReports(): HasMany
    {
        return $this->hasMany(IncidentReport::class, 'teacher_id');
    }

    public function counselingInterventions(): HasMany
    {
        return $this->hasMany(CounselingIntervention::class, 'counselor_id');
    }

    public function assetAssignments(): HasMany
    {
        return $this->hasMany(AssetAssignment::class);
    }

    public function getNameAttribute(): string
    {
        return trim($this->first_name.' '.$this->last_name);
    }

    public function setNameAttribute(string $value): void
    {
        $trimmed = trim($value);

        if ($trimmed === '') {
            return;
        }

        $parts = preg_split('/\s+/', $trimmed, 2) ?: [];
        $this->attributes['first_name'] = $parts[0] ?? '';
        $this->attributes['last_name'] = $parts[1] ?? '';
    }

    public function initials(): string
    {
        return Str::upper(Str::substr($this->first_name, 0, 1).Str::substr($this->last_name, 0, 1));
    }

    public function hasPermissionTo(string $actionName): bool
    {
        if ($this->roles()->where('name', 'admin')->exists()) {
            return true;
        }

        return $this->permissions()->where('action_name', $actionName)->exists();
    }
}

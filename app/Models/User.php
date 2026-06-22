<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'employee_type',
        'phone',
        'address',
        'photo',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // ─── Relationships ────────────────────────────────────

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function outsourcingEmployee()
    {
        return $this->hasOne(OutsourcingEmployee::class);
    }

    public function internshipParticipant()
    {
        return $this->hasOne(InternshipParticipant::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function permissions()
    {
        return $this->hasMany(Permission::class);
    }

    // ─── Helper Methods ───────────────────────────────────

    public function isAdmin(): bool
    {
        return $this->role->name === 'admin';
    }

    public function isEmployee(): bool
    {
        return $this->role->name === 'pegawai';
    }

    public function isOutsourcing(): bool
    {
        return $this->employee_type === 'outsourcing';
    }

    public function isMagang(): bool
    {
        return $this->employee_type === 'magang';
    }

    public function getPhotoUrlAttribute(): string
    {
        if ($this->photo) {
            return asset('storage/' . $this->photo);
        }
        return asset('images/default-avatar.png');
    }
}

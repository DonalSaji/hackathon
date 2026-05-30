<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
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

    public static function getpermissionGroups()
    {
        $permission_groups = DB::table('permissions')->where('group_name', '<>', 'permissions')->select('group_name')->groupBy('group_name')->get();
        return $permission_groups;
    }

    public static function getpermissionByGroupName($group_name)
    {

        $permissions = DB::table('permissions')
            ->select('name', 'id')
            ->where('group_name', $group_name)
            ->get();
        return $permissions;
    }


    public static function roleHasPermissions($role, $permissions)
    {

        $hasPermission = true;
        foreach ($permissions as $permission) {
            if (!$role->hasPermissionTo($permission->name)) {
                $hasPermission = false;
            }
            return $hasPermission;
        }
    }
    // User who added this user
    public function addedByUser()
    {
        return $this->belongsTo(User::class, 'addedBy');
    }

    // Users added by this user
    public function addedUsers()
    {
        return $this->hasMany(User::class, 'addedBy');
    }

    // public function profile()
    // {
    //     return $this->hasOne(UserProfile::class, 'user_id', 'id');
    // }

    //User to manage more than one task
    public function todos()
    {

        return $this->hasMany(Todo::class);
    }

    public function hasPermission($permissionName)
    {
        return $this->roles()
            ->with('permissions')
            ->get()
            ->pluck('permissions.*.name')
            ->flatten()
            ->contains($permissionName);
    }
}

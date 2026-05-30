<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role as SpatieRole;
use Illuminate\Support\Facades\DB;

class Role extends SpatieRole
{
    use HasFactory;
    public function delete()
    {
        $assignedUsers = DB::table('model_has_roles')
            ->where('role_id', $this->id)
            ->count();

        if ($assignedUsers > 0) {
            throw new \Exception("Cannot delete role '{$this->name}' because it has assigned users.");
        }

        return parent::delete();
    }
}

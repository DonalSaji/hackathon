<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Permission;

class FileController extends Controller
{
    public function viewFile($filename)
    {
        $user = Auth::user();

        if (!$user) {
            abort(403, 'Unauthorized');
        }

        $group = explode('/', $filename)[0];

        // Get allowed groups from config (null if not configured)
        $allowedGroups = config("file_access.folders.$group", null);

        $hasAccess = false;

        if ($allowedGroups !== null) {
            if (empty($allowedGroups)) {
                // Explicitly open to all logged-in users
                $hasAccess = true;
            } else {
                // Strict mode → check permissions
                $permissions = Permission::whereIn('group_name', $allowedGroups)->pluck('name');
                $hasAccess = $permissions->some(fn($perm) => $user->can($perm));
            }
        } else {
            // Not configured in file_access.php → default allow for all logged-in users
            $hasAccess = true;
        }

        if (!$hasAccess) {
            Log::warning("Unauthorized file access attempt by user ID {$user->id} for file {$filename} and group {$group}");
            abort(403, 'Unauthorized');
        }

        $path = "private/{$filename}";

        if (!Storage::exists($path)) {
            abort(404, 'File not found');
        }

        return response()->file(storage_path("app/{$path}"), [
            'Content-Type' => Storage::mimeType($path),
            'Content-Disposition' => 'inline; filename="' . basename($path) . '"'
        ]);
    }
}

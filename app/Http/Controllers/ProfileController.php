<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileUpdateRequest;
use App\Models\NotificationSetting;
use App\Models\User;
use App\Models\UserProfile;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;


class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = Auth::user();
        \Log::info($user);
        // activity()->event('access')->withProperties($user)->log('Accessed edit profile page');
        return view('backend.profile.profile', compact('user'));
        // return view('backend.profile.profile', [
        //     'user' => $request->user(),
        // ]);
    }

    public function uploadAvatar(Request $request)
    {
        try {
            $request->validate([
                'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validate image type and size
            ]);

            $user = UserProfile::where('user_id', Auth::user()->id)->first();


            // Delete the existing avatar if it exists
            if ($user && $user->avatar) {
                Storage::disk('private')->delete($user->avatar);
            }


            // Get the original file name and append a timestamp
            $originalFilename = pathinfo($request->file('avatar')->getClientOriginalName(), PATHINFO_FILENAME);
            $newFilename = $originalFilename . '_' . time() . '.' . $request->file('avatar')->getClientOriginalExtension();

            // Store the file with the custom name
            $path = $request->file('avatar')->storeAs('users', $newFilename, 'private');


            // Update the user's avatar path in the database
            $userProfile = UserProfile::updateOrCreate(
                ['user_id' => Auth::user()->id], // Criteria to check existing record
                [
                    'avatar' => $path,
                    'user_id' => Auth::user()->id,
                ]
            );

            // Log the avatar upload activity
            // activity()->performedOn($userProfile)->withProperties(['imagepath' => $path])->event('uploaded')->log('User uploaded a new profile image.');

            return response()->json([
                'success' => true,
                'message' => 'Avatar uploaded successfully.',
                'avatar_url' => route('files.view', $path),
            ]);
        } catch (ValidationException $e) {
            // Return validation errors as JSON
            // activity()->event('error')->withProperties($e->getMessage())->log('error validating avatar');
            return response()->json(['errors' => $e->errors()], 422);
        } catch (Exception $e) {
            // Handle other unexpected errors
            // logActivity('Update Profile Exception', ['path' => $path], $e->getMessage(), 'error');
            return response()->json(['error' => 'An unexpected error occurred.' . $e->getMessage()], 500);
        }
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request)
    {
        $id = Auth::user()->id;
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'dob' => 'nullable|date',
            'bio' => 'nullable|string|max:1000',
            'street_address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'pincode' => 'nullable|string|max:10',
        ]);

        try {
            $user = User::find($id);
            $user->update([
                'name' => $validatedData['name'],


            ]);

            UserProfile::updateOrCreate(
                ['user_id' => $id], // Condition to check existing profile
                [
                    'dob' => $validatedData['dob'],
                    'bio' => $validatedData['bio'],
                    'street_address' => $validatedData['street_address'],
                    'city' => $validatedData['city'],
                    'state' => $validatedData['state'],
                    'pincode' => $validatedData['pincode'],
                ]
            );

            // activity()->causedBy($user)->withProperties($validatedData)->event('updated')->log('User profile  updated');

            return redirect()->back()->with('success', 'Profile updated successfully!');
        } catch (Exception $e) {
            // activity()->event('error')->withProperties($e->getMessage())->log('An error occurred during profile update');

            return redirect()->back()->with(['error' => 'An error occurred: ' . $e->getMessage()]);
        }
    }


    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function NotificationSettings()
    {
        $user = User::with('roles.permissions')->find(Auth::id());

        $permissionGroups = collect();

        foreach ($user->roles as $role) {
            foreach ($role->permissions as $permission) {
                $permissionGroups->push($permission->group_name);
            }
        }
        $Notifications_groups = $permissionGroups->unique()->values();

        // ✅ Get saved groups as array
        $uncheckedNotifications = NotificationSetting::where('user_id', $user->id)
            ->value('notification_groups');

        $uncheckedNotifications = $uncheckedNotifications
            ? json_decode($uncheckedNotifications, true)
            : [];

        // activity()->event('access')->withProperties([$role,$permissions,$permission_groups])->log('Accessed roles in permission page');
        return view('backend.profile.notification-settings', compact('user',  'Notifications_groups', 'uncheckedNotifications'));
    }

    public function updateNotificationSettings(Request $request, $id): RedirectResponse
    {
        $validatedData = $request->validate([
            'notification_groups' => 'nullable|string',
        ]);

        try {
            $user = User::find($id);

            $notificationSettings = NotificationSetting::updateOrCreate(
                ['user_id' => $id], // Condition to check existing settings
                [
                    'notification_groups' => $validatedData['notification_groups'],
                ]
            );

            // $user = User::first();
            // $user->notify(new \App\Notifications\UserAlert("Your notification settings have been updated.", "/notification-settings", $user));

            // activity()->causedBy($user)->withProperties($validatedData)->event('updated')->log('User notification settings updated');

            return Redirect::route('notification.settings')->with('success', 'Notification settings updated successfully!');
        } catch (Exception $e) {
            // activity()->event('error')->withProperties($e->getMessage())->log('An error occurred during notification settings update');

            return Redirect::route('notification.settings')->with(['error' => 'An error occurred: ' . $e->getMessage()]);
        }
    }
}

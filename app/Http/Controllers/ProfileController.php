<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{

public function index(): View
    {

        return view('profile.index');
    }

    public function dashboard(): View
    {
        $user = Auth::user();
        $images = $user->images ? json_decode($user->images, true) : [];
        return view('dashboard', compact('user', 'images'));
    }
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
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
    // Store user image
   public function image(Request $request)
{
    // Validation
    $request->validate([
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'photos' => 'nullable|array',
        'photos.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    $user = Auth::user();
    $uploadedPaths = [];

    // Delete old single image
    if ($user->image) {
        $oldImagePath = public_path($user->image);
        if (file_exists($oldImagePath)) {
            unlink($oldImagePath);
        }
    }

    // Delete old multiple images
    if ($user->images) {
        $oldPhotos = json_decode($user->images, true);
        if (is_array($oldPhotos)) {
            foreach ($oldPhotos as $oldPhoto) {
                $oldPhotoPath = public_path($oldPhoto);
                if (file_exists($oldPhotoPath)) {
                    unlink($oldPhotoPath);
                }
            }
        }
    }

    // Single image upload
    if ($request->hasFile('image')) {
        $file = $request->file('image');
        $imageName = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('images/image'), $imageName);
        $uploadedPaths[] = 'images/image/' . $imageName;
        $user->image = 'images/image/' . $imageName;
    }

    // Multiple photos upload
    if ($request->hasFile('photos')) {
        foreach($request->file('photos') as $file) {
            $photoName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images/photos'), $photoName);
            $uploadedPaths[] = 'images/photos/' . $photoName;
        }
        $user->images = json_encode($uploadedPaths);
    }

    $user->save();

    return back()->with('success', 'Images uploaded successfully');
}


}


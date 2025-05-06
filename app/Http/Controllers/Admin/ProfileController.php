<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends BaseAdminController
{
    /**
     * Display the user's profile form.
     */
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
    $user = $request->user();

    // Cập nhật các trường thông tin khác trước
    $user->fill($request->validated());

    // Kiểm tra nếu email thay đổi
    if ($user->isDirty('email')) {
        $user->email_verified_at = null;
    }

    // Xử lý upload ảnh CCCD
    if ($request->hasFile('id_photo')) {
        // Chỉ xóa ảnh nếu file cũ nằm trong storage
        if ($user->id_photo && Storage::disk('public')->exists($user->id_photo)) {
            Storage::disk('public')->delete($user->id_photo);
        }

        // Lưu ảnh mới vào storage/public/id_photos
        $path = $request->file('id_photo')->store('id_photos', 'public');
        if ($path) {
            $user->id_photo = $path;
        }
    }

    // Xử lý upload ảnh avatar
    if ($request->hasFile('avatar')) {
        // Chỉ xóa avatar nếu file cũ nằm trong storage
        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }

        // Lưu ảnh mới vào storage/public/avatars
        $avatarPath = $request->file('avatar')->store('avatars', 'public');
        if ($avatarPath) {
            $user->avatar = $avatarPath;
        }
    }

    // Lưu cập nhật vào database
    $user->save();

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
}

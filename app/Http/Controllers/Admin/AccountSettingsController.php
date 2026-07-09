<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateAccountSettingsRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AccountSettingsController extends Controller
{
    public function edit(): View
    {
        return view('admin.settings.account', [
            'user' => auth()->user(),
        ]);
    }

    public function update(UpdateAccountSettingsRequest $request): RedirectResponse
    {
        $user = $request->user();
        $data = $request->validated();

        $user->email = $data['email'];

        if (! empty($data['password'])) {
            $user->password = $data['password'];
        }

        $user->save();

        return back()->with('success', empty($data['password'])
            ? 'Email admin berhasil diperbarui.'
            : 'Email dan password admin berhasil diperbarui.');
    }
}

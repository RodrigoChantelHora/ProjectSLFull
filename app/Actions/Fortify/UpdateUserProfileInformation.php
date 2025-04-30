<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class UpdateUserProfileInformation implements UpdatesUserProfileInformation
{
    /**
     * Validate and update the given user's profile information.
     *
     * @param  array<string, mixed>  $input
     */
    public function update(User $user, array $input): void
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'photo' => ['nullable', 'mimes:jpg,jpeg,png', 'max:1024'],
        ])->validateWithBag('updateProfileInformation');

        if (isset($input['photo'])) {
            $filename = $user->id . '_' . time() . '.' . $input['photo']->getClientOriginalExtension();
        
            // Define o caminho absoluto até public_html/profile-photos
            $destinationPath = base_path('public_html/profile-photos');
        
            // Cria a pasta se não existir
            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0755, true);
            }
        
            // Apaga a foto antiga, se existir
            if ($user->profile_photo_path && File::exists(base_path('public_html/' . $user->profile_photo_path))) {
                File::delete(base_path('public_html/' . $user->profile_photo_path));
            }
        
            // Move o novo arquivo para a pasta
            $input['photo']->move($destinationPath, $filename);
        
            // Salva o caminho relativo no banco (usado com asset())
            $user->forceFill([
                'profile_photo_path' => 'profile-photos/' . $filename,
            ])->save();
        }



        if ($input['email'] !== $user->email &&
            $user instanceof MustVerifyEmail) {
            $this->updateVerifiedUser($user, $input);
        } else {
            $user->forceFill([
                'name' => $input['name'],
                'email' => $input['email'],
            ])->save();
        }
    }

    /**
     * Update the given verified user's profile information.
     *
     * @param  array<string, string>  $input
     */
    protected function updateVerifiedUser(User $user, array $input): void
    {
        $user->forceFill([
            'name' => $input['name'],
            'email' => $input['email'],
            'email_verified_at' => null,
        ])->save();

        $user->sendEmailVerificationNotification();
    }
}

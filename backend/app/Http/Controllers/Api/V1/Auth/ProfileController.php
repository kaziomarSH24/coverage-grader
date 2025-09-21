<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\UpdateProfileRequset;
use App\Services\AuthService;
use App\Traits\FileUploadTrait;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

/**
 * @group Profile
 * Manage the authenticated user's profile information.
 */
class ProfileController extends Controller
{
    use FileUploadTrait;
    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function me(Request $request)
    {
        $user = $request->user()->load('roles', 'permissions');
        return response_success('User data fetched successfully.', $user);
    }

    public function updateProfile(UpdateProfileRequset $request)
    {

        $validated = $request->validated();

        try {

            //image file upload handled in service
            if ($request->hasFile('avatar')) {
                //remove old avatar if exists
                if ($request->user()->avatar) {
                    $this->deleteFile($request->user()->avatar);
                }
                $path = $this->handleFileUpload($request, 'avatar', 'avatars');
                $validated['avatar'] = $path;
            }

            $user = $this->authService->updateProfile(
                $request->user(),
                $validated,
                $request
            );
            return response_success('Profile updated successfully.', $user);
        } catch (ValidationException $e) {
            return response_error('Validation failed.', $e->errors(), 422);
        }
    }
}

<?php


namespace App\Services;


use App\Models\User;
use App\Exceptions\InvalidArgumentException;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

class UserService
{
    /**
     * updateProfile
     *
     * @param User $user
     * @param Request $request
     * @return User
     * @throws InvalidArgumentException
     */
    public function updateProfile(User $user, $request)
    {
        if ($request->file('value')) {
            return $this->updateAvatar($user, $request->file('value'));
        }

        $column = $request->input('column');

        $user->$column = $request->input('value');

        $user->save();

        return $user;
    }

    /**
     * updateAvatar
     *
     * @param User $user
     * @param UploadedFile $avatar
     * @return User
     * @throws InvalidArgumentException
     */
    public function updateAvatar(User $user, $avatar)
    {
        $filename = hash('sha256', "{$user->id}_". time());
		
		$filename .= '.'.$avatar->extension();

        $path = $avatar->storeAs('avatar', $filename, 'public');

        if ($path === false) {
            throw new InvalidArgumentException('图片存储失败');
        }

        $user->headimgurl = $filename;

        $user->save();

        return $user;
    }
}

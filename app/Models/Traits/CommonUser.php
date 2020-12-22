<?php

namespace App\Models\Traits;

use App\Exceptions\ApiResponseException;
use App\Models\Admin;
use App\Models\Property;
use App\Rules\PasswordValidateRule;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Spatie\Permission\Traits\HasRoles;

trait CommonUser
{
    use HasDepartment, HasRoles;

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = \Hash::make($value);
    }

    public function scopeSearch(Builder $query, Request $request)
    {
        if ($name = $request->input('name')) {
            $query->where('name', 'like', "%{$name}%");
        }

        if ($recode = $request->input('recode')) {
            $query->where('code', $recode);
        }

        if ($department_name = $request->input('department_name')) {
            $query->whereHas('department', function ($query) use ($department_name) {
                $query->where('name', 'like', "%{$department_name}%");
            });
        }

        if ($mobile = $request->input('mobile')) {
            $query->where('mobile', 'like', "%{$mobile}%");
        }

        return $query;
    }

    /**
     * 添加用户
     *
     * @param Request $request
     * @throws ApiResponseException
     */
    public function addUser(Request $request)
    {
        $query = static::query();

        $exists = $query->where(function ($query) use ($request) {
            $query->orWhere('email', $request->input('email'))
                ->orWhere('mobile', $request->input('mobile'));
            // ->orWhere('name', $request->input('name'));
        })->exists();

        if ($exists) {
            throw new ApiResponseException('用户已存在，不能重复添加', 40022);
        }

        \DB::transaction(function () use ($request) {
            activity()->enableLogging(); // 开启记录操作日志

            $model = static::class;
            /* @var Admin|Property $admin  */
            $admin = new $model($this->ignore($request));

            $admin->save();

            $admin->sync($request);

            return $admin;
        });
    }

    /**
     * 针对物业用户忽略停车场id
     *
     * @param Request $request
     * @return array
     */
    protected function ignore(Request $request)
    {
        $user = $request->user();

        $params = $request->all();

        // 物业自己无法修改所属停车场
        if ($user instanceof Property) {
            $params['park_id'] = $user->park_id;
        }

        return $params;
    }

    /**
     * 更新用户信息
     *
     * @param Request $request
     * @throws ApiResponseException
     */
    public function updateUser(Request $request)
    {
        $query = static::query();

        $exists = $query->where(function ($query) use ($request) {
            $query->orWhere('email', $request->input('email'))
                ->orWhere('mobile', $request->input('mobile'));
            // ->orWhere('name', $request->input('name'));
        })
            ->where('id', '<>', $this->getKey())
            ->exists();

        if ($exists) {
            throw new ApiResponseException('用户已存在，不能重复添加', 40022);
        }

        \DB::transaction(function () use ($request) {
            activity()->enableLogging(); // 开启记录操作日志

            /* @var Admin|Property $admin  */
            $admin = $this->fill($this->ignore($request));

            $admin->save();

            $admin->sync($request);
        });
    }

    /**
     * sync
     *
     * @param Request $request
     */
    protected function sync(Request $request)
    {
        // 分配部门
        $this->departments()->sync(explode(',', $request->input('departments')));

        // 分配角色
        $this->syncRoles(explode(',', $request->input('roles')));
    }

    /**
     * 重置密码
     *
     * @param Request $request
     * @return $this
     */
    public function reset(Request $request)
    {
        $request->validate([
            'password'         => ['required', 'string', 'min:8', new PasswordValidateRule()],
            'password_confirm' => 'required|string|same:password',
        ]);

        $this->password = $request->input('password');

        $this->save();

        // 重置密码后，需要重新登录
        $this->tokens()->delete();

        return $this;
    }
}

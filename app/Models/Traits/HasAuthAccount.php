<?php

namespace App\Models\Traits;

use App\Exceptions\AccountNotFundException;
use App\Models\Users\UserAuthAccount;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasAuthAccount
{
    /**
     * authAccount
     *
     * @return HasMany
     */
    public function authAccount()
    {
        return $this->hasMany(UserAuthAccount::class);
    }

    /**
     * getAuth
     *
     * @param $from
     * @return \Illuminate\Database\Eloquent\Model|HasMany|object|null
     */
    public function getAuthAccount($from)
    {
        return $this->authAccount()
            ->where('from', $from)
            ->firstOr(function () {
                throw new AccountNotFundException();
            });
    }

    public function openid($from = 'wechat')
    {
        $account = $this->getAuthAccount($from);

        return $account['openid'];
    }
}

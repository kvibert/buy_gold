<?php

namespace App\Http\Middleware;

use Closure;
use App\Exceptions\CheckMbrException;

class AuthMember
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next,...$guards)
    {
        $this->isMemberLock($guards);
        return $next($request);
    }

    /**
     * @see 用户是否处于交易被锁定
     */
    public function isMemberLock($guards)
    {
        if (\Auth::user()->status == 2 || \Auth::user()->status == 3) {
            session()->flash("lock", "还有订单交易未完成！无法继续交易");
            throw new CheckMbrException(
                '还有订单交易未完成！无法继续交易', $guards, route('trade_record', ['show' => \Auth::user()->status])
            );
        }
    }
}

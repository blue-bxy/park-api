<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Models\Users\Message;
use Closure;

class NoticeListener
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        /** @var User $user */
        $user = $request->user();

        if ($user) {
            $query = $user->messages()->getQuery();

            $query->whereIn('type', [0, 2]); // 系统+App

            $query->withTrashed();

            $user_ids = $query->pluck('message_id');

            $query = Message::query();

            $query->whereIn('send_type', [0, 2]); // 系统+App

            $query->where(function ($query) {
                $query->orWhereNull('send_time')->orWhere(function ($query) {
                    $query->whereNotNull('send_time')->where('send_time', '<', now());
                });
            });

            // 排除创建账户之前的通知
            $query->where('created_at', '>', $user->created_at);

            $query->oldest();

            $query->whereNotIn('id', $user_ids);

            $messages = $query->get();

            $messages->each(function ($message) use ($user) {
                $user->messages()->create([
                    'type' => 0,
                    'title' => $message->title,
                    'content' => $message->content,
                    // 'imgurl' => $message->imgurl
                    'message_id' => $message->getKey(),
                ]);
            });

        }
        return $next($request);
    }
}

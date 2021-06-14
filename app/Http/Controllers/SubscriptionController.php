<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Subscription;

class SubscriptionController extends Controller
{
    public function get_user_subscriptions(Request $request)
    {
        return \DB::table('subscriptions')->where('user_id', $this->getUser($request)->id)->get();
    }
    public function post_user_subscription(Request $request, $id)
    {
        if(!$this->isLogged($request)){
            return response([
                'message' => 'You have no auth'
            ], 401);
        }

        $user_id = $this->getUser($request)->id;

        $subs = \DB::table('subscriptions')->where('post_id', $id)->where('user_id', $user_id)->get();
        if($subs != "[]") $this->delete_user_subscription($request, $id);
        else if($subs == "[]") {
            $subscription = new Subscription();
            $subscription->user()->associate($user_id);
            $subscription->post()->associate($id);
            $subscription->save();
        }
        return $subscription;
    }
    public function delete_user_subscription(Request $request, $id)
    {
        if(!$this->isLogged($request)){
            return response([
                'message' => 'You have no auth'
            ], 401);
        }

        $user_id = $this->getUser($request)->id;

        $subs = \DB::table('subscriptions')->where('post_id', $id)->where('user_id', $user_id)->get();

        Subscription::destroy($subs[0]->id);
    }
}

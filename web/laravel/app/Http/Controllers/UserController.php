<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;
use App\User;
use App\Follower;
use App\Notification;
use App\Like;
use App\Userthumbnail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use PhpParser\Node\Expr\Cast\String_;

class UserController extends Controller
{
    public function __construct()
    {
        // 認証が必要
        $this->middleware('auth')->only(['myacount', 'follow']);
    }

    public function myacount()
    {
        Auth::user()->userthumbnail;
        Auth::user()->followers;
        $user = Auth::user();
        return $user;
    }

    public function list()
    {
        $users = User::with(['products' => function ($query) {
            $query->get();
        }, 'userthumbnail', 'followers'])->orderBy(User::CREATED_AT, 'desc')->paginate();
        return $users;
    }

    public function products(String $id)
    {
        $products = Product::where('user_id', $id)->orderBy('created_at', 'desc')->paginate();
        return $products;
    }
    public function likeproducts(String $id)
    {
        $likeproducts = Product::whereHas('likes', function ($query) use ($id) {
            $query->where('user_id', $id);
        })->orderBy('created_at', 'desc')->paginate();
        return $likeproducts;
    }

    public function currentuser(String $id)
    {
        $user = User::with('userthumbnail')->where('id', $id)->get();
        $followerCount = Follower::where('user_id', $id)->count();
        $followeeCount = Follower::where('followee_id', $id)->count();
        $currentuser = $user->concat([$followerCount])->concat([$followeeCount]);
        return $currentuser;
    }

    public function updateuser(Request $request)
    {
        $userid = Auth::id();
        $user = User::where('id', $userid)->first();
        $user->fill($request->all())->save();
        return $user;
    }

    public function follow(String $id)
    {
        $userid = Auth::id();
        $followed = Follower::where('user_id', $userid)->where('followee_id', $id)->first();
        if (!$followed === null) {
            $followed->delete();
        }
        $follow = new Follower();
        $follow->user_id = $userid;
        $follow->followee_id = $id;
        $follow->save();
        return ["followee_id" => $id];
    }

    public function unfollow(String $id)
    {
        $userid = Auth::id();
        $followed = Follower::where('user_id', $userid)->where('followee_id', $id)->first();
        if (!$followed == null) {
            $followed->delete();
        }
    }

    public function followlist(String $id)
    {
        $follows = Follower::where('user_id', $id)->select('followee_id')->get();
        $users = User::wherein('id', $follows)->with(['products' => function ($query) {
            $query->get();
        }, 'userthumbnail', 'followers'])->orderBy(User::CREATED_AT, 'desc')->paginate();
        return $users;
    }

    public function followerlist(String $id)
    {
        $followers = Follower::where('followee_id', $id)->select('user_id')->get();
        $users = User::wherein('id', $followers)->with(['products' => function ($query) {
            $query->get();
        }, 'userthumbnail', 'followers'])->orderBy(User::CREATED_AT, 'desc')->paginate();
        return $users;
    }

    public function notificationInput(Request $request)
    {
        $notification = new Notification();
        $notification->opponent_id = Auth::id();
        $notification->user_id = $request->id;
        $notification->message = $request->message;
        $notification->save();
        return $notification;
    }

    public function notificationsList()
    {
        $id = Auth::id();
        $notification = Notification::where('user_id', $id)->get();
        return $notification;
    }

    public function notificationChecked(String $id)
    {
        $notification = Notification::find($id);
        $notification->checked = true;
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

//追加
use App\Models\User;
use Auth;

class FollowController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    //User.phpで定義したfollowings()の関数を呼び出す。attach関数で動かす。
    public function store(User $user)
    {
    Auth::user()->followings()->attach($user->id);
    return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
    // ターゲットユーザのデータを見つける
    $user = User::find($id);
    // ターゲットユーザのフォロワー一覧を取り出す。followers（自分をフォローしている人）の関数で該当のユーザーの情報を取る
    $followers = $user->followers;
    // ターゲットユーザのフォローしている人一覧を取り出す。followings（自分がフォローしている人）の関数で該当のユーザーの情報を取る
    $followings  = $user->followings;

    return response()->view('user.show', compact('user', 'followers', 'followings'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    //User.phpで定義したfollowings()の関数を呼び出す。detach関数で動かす。
    public function destroy(User $user)
    {
    Auth::user()->followings()->detach($user->id);
    return redirect()->back();
    }
}

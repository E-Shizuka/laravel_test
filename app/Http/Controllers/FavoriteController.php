<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

//いいね機能の処理するために連携させたデータとログイン情報を追加
use App\Models\Tweet;
use Auth;

class FavoriteController extends Controller
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

    // いいね追加の処理（`store()` の `()` 内も書き替える必要があるので注意）
    public function store(Tweet $tweet)
    {
        //ユーザーの情報を取ってきたらattach()で()内に記載したidを取ってくる
        $tweet->users()->attach(Auth::id());
        return redirect()->route('tweet.index');
    }

    // 追加済みのいいね削除の処理
    public function destroy(Tweet $tweet)
    {
        //detach()すると()内のデータを消す
        $tweet->users()->detach(Auth::id());
        return redirect()->route('tweet.index');
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
    // public function destroy(string $id)
    // {
    //     //
    // }
}

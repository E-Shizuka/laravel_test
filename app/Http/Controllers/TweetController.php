<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Validator;
use App\Models\Tweet;

use Auth; //ログイン情報を取得する場合はこれを追加

use App\Models\User; //ログインユーザーとツイートを紐付ける

class TweetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() //一覧画面を表示する時
    {
        //tweetフォルダのindexの画面を表示する ->view('ファイルのあるフォルダ.表示したいファイル')
        $tweets = Tweet::getAllOrderByUpdated_at();
        // ddd($tweets); //情報を確認できる。一番下の方。実行されたsql文もここで見れる
        return response()->view('tweet.index',compact('tweets')); 
        //compact('変数名を文字列で記入')で取ってきた情報をここに書き込む
    }

    public function timeline() //フォローしている人だけのタイムラインを表示する時
    {
    // フォローしているユーザを取得する（ログインしている人がフォローしている人のidを配列形式で取得）
    $followings = User::find(Auth::id())->followings->pluck('id')->all();
    // 自分と上で取得したフォローしている人の投稿したツイートを取得する。複数あるものを取りたいときはwhereで指定。
    $tweets = Tweet::query()
        ->where('user_id', Auth::id()) //自分とフォローしている人（複数）の条件から探す
        ->orWhereIn('user_id', $followings) //自分と配列（フォローしている人（複数）の情報）を取得
        ->orderBy('updated_at', 'desc') //並び替え
        ->get();
    return response()->view('tweet.index', compact('tweets'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() //データを入力する画面を表示する時
    {
        //
        return response()->view('tweet.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) //ツイートを送信した時
    {
        // バリデーション 条件分岐（データがあるか？データが揃っているか？）
        $validator = Validator::make($request->all(), [
            'tweet' => 'required | max:191',
            'description' => 'required',
        ]);
        // バリデーション:エラー
        if ($validator->fails()) {
            return redirect()
            ->route('tweet.create')
            ->withInput()
            ->withErrors($validator);
        }
        // ddd(Auth::user()->id);
        $data = $request->merge(['user_id' => Auth::user()->id])->all();

        // create()は最初から用意されている関数
        // 戻り値は挿入されたレコードの情報
        $result = Tweet::create($request->all());
        // ルーティング「tweet.index」にリクエスト送信（一覧ページに移動）
        return redirect()->route('tweet.index');
            }

    /**
     * Display the specified resource.
     */
    public function show(string $id) //詳細を表示する時
    {
        //find(関数)→関数を取り出す
        $tweet = Tweet::find($id);
        return response()->view('tweet.show', compact('tweet')); //取ってきたidの値をtweet.showへ渡す
    }

    

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id) //編集する時
    {
        $tweet = Tweet::find($id);
        return response()->view('tweet.edit', compact('tweet')); //取ってきたidの値をtweet.editへ渡す
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id) //更新する時
    {
        //バリデーション
        $validator = Validator::make($request->all(), [
            'tweet' => 'required | max:191',
            'description' => 'required',
        ]);
        //バリデーション:エラー
        if ($validator->fails()) {
            return redirect()
            ->route('tweet.edit', $id)
            ->withInput()
            ->withErrors($validator);
        }
        //データ更新処理
        $result = Tweet::find($id)->update($request->all());
        return redirect()->route('tweet.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id) //削除する時
    {
        $result = Tweet::find($id)->delete();
        return redirect()->route('tweet.index');
    }

    //マイページ
    public function mydata()
    {
        // Userモデルに定義したリレーションを使用してデータを取得する．
        $tweets = User::query()
        ->find(Auth::user()->id)
        ->userTweets()
        ->orderBy('created_at','desc')
        ->get();
        return response()->view('tweet.index', compact('tweets'));
    }
}

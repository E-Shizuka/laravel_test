<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tweet extends Model
{
    use HasFactory;

    protected $guarded = [
    'id',
    'created_at',
    'updated_at',
  ]; //$guardedはデータを入れちゃいけないカラムを指定

  public static function getAllOrderByUpdated_at()
  {
    return self::orderBy('updated_at', 'desc')->get();
  }  //データを取り出す時のルールを関数で設定しておく。->get()を忘れると動かないので注意！

    //     protected $fillable = [
    //     'tweet',
    //     'description',
    //   ]; //$fillableはデータを入れていいカラムを指定


  public function user()
  {
    return $this->belongsTo(User::class);
  } //ツイートモデルのファイル 対 ユーザー1 belongsTo(User::class)/

  //いいね機能のために追加。データを連携させる
  public function users()
  {
    return $this->belongsToMany(User::class)->withTimestamps();
  }


}

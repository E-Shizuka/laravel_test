<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    //ユーザーとツイートを紐付ける
    public function userTweets()
    {
        //ユーザー１対多 hasMany(Tweet::class)
        return $this->hasMany(Tweet::class);
    }

    //いいね機能のために追加。データを連携させる
    public function tweets()
    {
        return $this->belongsToMany(Tweet::class)->withTimestamps();
    }

    //フォロー機能のために追加。データを連携させる。
    //多対多だけど同じもの（ユーザー）連携させる→「self::class」で記載。followsテーブルからログインしている人の情報（user_id）とログインしている人がフォローしている人のid
    public function followings()
    {
        return $this->belongsToMany(self::class, "follows", "user_id", "following_id")->withTimestamps();
    }
    
    //多対多だけど同じもの（ユーザー）連携させる→「self::class」で記載。followsテーブルからログインしている人の情報（user_id）とログインしている人をフォローしている人のid
    public function followers()
    {
    return $this->belongsToMany(self::class, "follows", "following_id", "user_id")->withTimestamps();
    }
}

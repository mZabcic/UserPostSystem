<?php

/**
 * Class User
 *
 * @package RZNU
 *
 * @author  Mislav Žabčić <mislav.zabcic@gmail.com>
 *
 * @OA\Schema(
 *     description="User model",
 *     title="User",
 *     required={"first_name", "last_name", "email", "password", "dateofbirth"},
 * )
 */

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;



class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name','last_name', 'email', 'password', 'date_of_birth'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    public function getJWTIdentifier()
        {
            return $this->getKey();
        }

    public function getJWTCustomClaims()
    {
        return [];
    }


    public function following()
    {
        return $this->belongsToMany(User::class, 'relationship', 'follower_id', 'following_id')->withTimestamps();
    }

    public function followers()
    {
        return $this->belongsToMany(User::class, 'relationship', 'following_id', 'follower_id')->withTimestamps();
    }
  

    /**
     * @OA\Property(
     *     format="int64",
     *     title="ID",
     * )
     *
     * @var integer
     */
    private $id;

    /**
     * @OA\Property(
     *     title="First name",
     * )
     *
     * @var string
     */
    private $first_name;

    /**
     * @OA\Property(
     *     title="Last name",
     * )
     *
     * @var string
     */
    private $last_name;

    /**
     * @OA\Property(
     *     title="Email address",
     * )
     *
     * @var string
     */
    private $email;

    /**
     * @OA\Property(
     *     title="Password",
     * )
     *
     * @var string
     */
    private $password;

     /**
     * @OA\Property(
     *     title="Date of birth",
     *     format="date",
     *     type="string"
     * )
     *
     * @var \Date
     */
    private $date_of_birth;


     /**
     * @OA\Property(
     *     title="Created at",
     *     format="datetime",
     *     type="string"
     * )
     *
     * @var \DateTime
     */
    private $created_at;

    /**
     * @OA\Property(
     *     title="Updated at",
     *     format="datetime",
     *     type="string"
     * )
     *
     * @var \DateTime
     */
    private $updated_at;
}

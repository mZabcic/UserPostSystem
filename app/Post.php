<?php

/**
 * Class Post
 *
 * @package RZNU
 *
 * @author  Mislav Žabčić <mislav.zabcic@gmail.com>
 *
 * @OA\Schema(
 *     description="Post",
 *     title="Post",
 *     required={"title", "content"},
 * )
 */

namespace App;


use Illuminate\Database\Eloquent\Model;

class Post extends Model
{


    protected $table = 'posts';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title','content', 'picture', 'user_id'
    ];

     /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'picture',
    ];
 
    public function user()
    {
        return $this->belongsTo('App\User','user_id', 'id');
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
     *     title="Title",
     * )
     *
     * @var string
     */
    private $title;

    /**
     * @OA\Property(
     *     title="Content",
     * )
     *
     * @var string
     */
    private $content;

    /**
     * @OA\Property(
     *     title="Picture",
     * )
     *
     * @var binary
     */
    private $picture;

    /**
     * @OA\Property(
     *     format="int64",
     *     title="User Id",
     * )
     *
     * @var integer
     */
    private $user_id;

    /**
     * @OA\Property(
     *     title="User",
     * )
     *
     * @var User
     */
    private $user;


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

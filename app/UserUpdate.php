<?php

/**
 * Class UserUpdate
 *
 * @package RZNU
 *
 * @author  Mislav Žabčić <mislav.zabcic@gmail.com>
 *
 * @OA\Schema(
 *     description="User model for update",
 *     title="User update",
 * )
 */

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;



class UserUpdate
{
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


}

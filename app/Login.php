<?php

/**
 * Class Login
 *
 * @package RZNU
 *
 * @author  Mislav Žabčić <mislav.zabcic@gmail.com>
 *
 * @OA\Schema(
 *     description="Login",
 *     title="Login model",
 *     required={"email", "password"}
 * )
 */

namespace App;

use Illuminate\Notifications\Notifiable;



class Login
{
    

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

     
}

<?php

/**
 * Class Register
 *
 * @package RZNU
 *
 * @author  Mislav Žabčić <mislav.zabcic@gmail.com>
 *
 * @OA\Schema(
 *     description="Register model",
 *     title="Register",
 *     required={"first_name", "last_name", "email", "password", "date_of_birth"}
 * )
 */
namespace App;

use Illuminate\Notifications\Notifiable;



class Register
{
    
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

     
}

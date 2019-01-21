<?php

/**
 * Class Token
 *
 * @package RZNU
 *
 * @author  Mislav Žabčić <mislav.zabcic@gmail.com>
 *
 * @OA\Schema(
 *     description="Register and login api response",
 *     title="AuthResponse"
 * )
 */
namespace App;


class AuthResponse
{
     /**
     * @OA\Property(
     *     description="User authentificated",
     *     title="User",
     * )
     *
     * @var User
     */
    private $user;

    /**
     * @OA\Property(
     *     description="JWT token",
     *     title="Token"
     * )
     *
     * @var string
     */
    private $token;
}
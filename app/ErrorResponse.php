<?php

/**
 * Class Error
 *
 * @package RZNU
 *
 * @author  Mislav Žabčić <mislav.zabcic@gmail.com>
 *
 * @OA\Schema(
 *     description="Error api response",
 *     title="Error"
 * )
 */
namespace App;


class Error
{
    /**
     * @OA\Property(
     *     description="Error message",
     *     title="Error"
     * )
     *
     * @var string
     */
    private $error;
}
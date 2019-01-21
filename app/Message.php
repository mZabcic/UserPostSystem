<?php

/**
 * Class Message
 *
 * @package RZNU
 *
 * @author  Mislav Žabčić <mislav.zabcic@gmail.com>
 *
 * @OA\Schema(
 *     description="Message api response",
 *     title="Message"
 * )
 */
namespace App;


class Message
{
    /**
     * @OA\Property(
     *     description="Message message",
     *     title="Message"
     * )
     *
     * @var string
     */
    private $message;
}
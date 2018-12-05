<?php

/**
 * Class PostUpdateImage
 *
 * @package RZNU
 *
 * @author  Mislav Žabčić <mislav.zabcic@gmail.com>
 *
 * @OA\Schema(
 *     description="Post image update",
 *     title="Post update image",
 *     required={"picture"},
 * )
 */

namespace App;




class PostUpdateImage
{

    /**
     * @OA\Property(
     *     title="Picture"
     * )
     *  @var string
     */
    private $picture;


}

<?php

/**
 * Class PostUpdate
 *
 * @package RZNU
 *
 * @author  Mislav Žabčić <mislav.zabcic@gmail.com>
 *
 * @OA\Schema(
 *     description="Post update",
 *     title="Post update",
 *     required={"title", "content"},
 * )
 */

namespace App;




class PostUpdate
{
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

   


}

<?php

/**
 * Class Post
 *
 * @package RZNU
 *
 * @author  Mislav Žabčić <mislav.zabcic@gmail.com>
 *
 * @OA\Schema(
 *     description="Post create",
 *     title="Post create",
 *     required={"title", "content"},
 * )
 */

namespace App;




class PostCreate
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

    /**
     * @OA\Property(
     *     title="Picture"
     * )
     *  @var string
     */
    private $picture;


}

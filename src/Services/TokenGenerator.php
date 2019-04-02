<?php
/**
 * Created by PhpStorm.
 * User: Keigo
 * Date: 02/04/2019
 * Time: 13:18
 */

namespace App\Services;


class TokenGenerator
{
    /**
     * Generates random token
     * @return string
     * @throws \Exception
     */
    public function generate()
    {
        return sha1(random_bytes(10));
    }

}
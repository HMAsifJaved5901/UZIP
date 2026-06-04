<?php

namespace App\Http\Controllers;

/**
 * @OA\Info(
 *     title="APIs For UZIPW",
 *     version="1.0.0"
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     in="header",
 *     name="Authorization"
 * )
 *
 */

abstract class Controller
{
    //
}

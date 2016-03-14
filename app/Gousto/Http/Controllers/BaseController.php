<?php

namespace Gousto\Http\Controllers;

use Dingo\Api\Routing\Helpers;
use Gousto\Http\Helpers\Pagination;
use Laravel\Lumen\Routing\Controller;

/**
 * Class BaseController
 * @package Gousto\Http\Controllers
 */
class BaseController extends Controller
{
    use Helpers, Pagination;
}

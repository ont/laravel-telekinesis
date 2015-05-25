<?php namespace Ont\Telekinesis;

use App\Http\Controllers\Controller;
use App\User;

use \Input;  // for avoiding "\" at beginning of facade's name

class MainController extends Controller {
    public function index(Invoker $invoker)
    {
        return $invoker->run( Input::json()->all() );
    }
}

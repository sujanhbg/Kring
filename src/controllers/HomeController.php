<?php

use kring\core\Controller;

class HomeController extends Controller {

    function index($pr) {
        return $this->rendTxt("Get this home method with ");
    }

}

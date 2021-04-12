<?php

namespace App\Http\Controllers\Voyager;

use Validator;
use Illuminate\Http\Request;
use TCG\Voyager\Facades\Voyager;
use TCG\Voyager\Events\BreadDataAdded;
use TCG\Voyager\Events\BreadDataUpdated;
use TCG\Voyager\Http\Controllers\VoyagerBaseController;
use TCG\Voyager\Http\Controllers\Traits\BreadRelationshipParser;

class OtherController extends VoyagerBaseController
{
    use BreadRelationshipParser;

    public function index(Request $request)
    {
        $view = 'voyager::bread.browse';

        if (view()->exists("voyager::other.browse")) {
            $view = "voyager::other.browse";
        }

        return Voyager::view($view);
    }

}
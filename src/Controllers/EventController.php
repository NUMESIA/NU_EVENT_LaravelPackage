<?php

namespace Numesia\NuEvent\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class EventController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     *
     * @return Response
     */
    public function createInternalEvent(Request $request)
    {
        if ($request->nuEventToken != env('NUEVENT_TOKEN')) {
            return;
        }

        event("nuEvents:{$request->name}", [$request->data]);
    }
}

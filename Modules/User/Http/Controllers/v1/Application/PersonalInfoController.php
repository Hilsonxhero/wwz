<?php

namespace Modules\User\Http\Controllers\v1\Application;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\User\Transformers\App\PersonalInfoResource;

class PersonalInfoController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $user = auth()->user();
        return new PersonalInfoResource($user);
    }
}
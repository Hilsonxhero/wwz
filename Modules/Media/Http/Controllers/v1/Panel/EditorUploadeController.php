<?php

namespace Modules\Media\Http\Controllers\v1\Panel;

use Illuminate\Support\Str;
use App\Services\ApiService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Contracts\Support\Renderable;

class EditorUploadeController extends Controller
{

    public function __invoke(Request $request)
    {
        $image = $request->file('file');

        $image_name = Str::random(15) . '.' . $image->getClientOriginalExtension();

        $image_dir = storage_path() . '/app/public/editor/';

        $image->move($image_dir, $image_name);

        ApiService::_success(asset('/storage/editor/' . $image_name));
    }
}

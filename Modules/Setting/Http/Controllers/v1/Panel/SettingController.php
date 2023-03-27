<?php

namespace Modules\Setting\Http\Controllers\v1\Panel;

use App\Services\ApiService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Intervention\Image\Facades\Image;
use Modules\Setting\Entities\Setting;
use Illuminate\Support\Facades\Storage;
use Modules\Media\Services\MediaFileService;
use Modules\Setting\Http\Requests\SettingRequest;
use Modules\Setting\Transformers\SettingResource;
use Modules\Setting\Repository\SettingRepositoryInterface;

class SettingController extends Controller
{
    private $settingrRepo;
    public function __construct(SettingRepositoryInterface $settingrRepo)
    {
        $this->settingrRepo = $settingrRepo;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $settings = $this->settingrRepo->all();
        return SettingResource::collection($settings);
    }

    public function isJson($string)
    {
        json_decode($string, true);
        return json_last_error() === JSON_ERROR_NONE;
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @return Response
     */
    public function update(Request $request)
    {
        $options = config('setting.options');
        $settings = [];
        foreach ($options as $option) {
            if ($request->{$option}) {
                $value = $this->isJson($request->input($option)) ? json_encode(json_decode($request->input($option), true)) : json_encode($request->input($option));
                if ($request->file($option)) {
                    $setting = $this->settingrRepo->find($option);
                    if ($setting) {
                        $setting->clearMediaCollectionExcept();
                    } else {
                        $setting = Setting::query()->create(['name' => $option]);
                    }
                    $setting->addMedia($request->{$option})->toMediaCollection();

                    $value = json_encode($setting->getFirstMediaUrl());
                }
                array_push($settings, ['name' => $option, 'value' => $value]);
            }
        }
        Setting::set($settings);
        ApiService::_success(trans('response.responses.200'));
    }
}

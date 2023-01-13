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

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @return Response
     */
    public function update(Request $request)
    {
        // return $request->file('logo');
        // return $request->file('logo')->getClientOriginalExtension();
        // $media = MediaFileService::publicUpload($request->file('logo'), 'static');
        // ApiService::_success(asset($media->files['original']));
        $options = config('setting.options');
        $settings = [];

        foreach ($options as $option) {
            if (base64($request->{$option})) {
                // $setting = $this->settingrRepo->find($option);
                // $setting->clearMediaCollectionExcept('main');
                // $setting->addMediaFromBase64($request->{$option})->toMediaCollection('main');
            }
            if ($request->{$option}) {
                $value = json_encode($request->input($option));
                if ($request->file($option)) {
                    $setting = $this->settingrRepo->find($option);
                    if ($setting) {
                        MediaFileService::delete($setting->value);
                    }
                    // $setting = $this->settingrRepo->find($option);
                    // if ($setting) $setting->clearMediaCollectionExcept('main');
                    // $setting->addMedia($request->{$option})->toMediaCollection('main');

                    $media = MediaFileService::publicUpload($request->file($option), 'static');
                    $value = $media->files['original'];
                }
                array_push($settings, ['name' => $option, 'value' => $value]);
            }
        }
        Setting::set($settings);
        ApiService::_success(trans('response.responses.200'));
    }
}
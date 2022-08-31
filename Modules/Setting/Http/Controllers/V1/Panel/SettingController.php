<?php

namespace Modules\Setting\Http\Controllers\v1\Panel;

use App\Services\ApiService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Setting\Entities\Setting;
use Modules\Setting\Http\Requests\SettingRequest;
use Modules\Setting\Repository\SettingRepositoryInterface;
use Modules\Setting\Transformers\SettingResource;

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
    public function update(SettingRequest $request)
    {
        $options = config('setting.options');
        $settings = [];

        foreach ($options as $option) {
            if (base64($request->{$option})) {
                $setting = $this->settingrRepo->find($option);
                $setting->clearMediaCollectionExcept('main');
                $setting->addMediaFromBase64($request->{$option})->toMediaCollection('main');
            }
            if ($request->{$option}) {
                array_push($settings, ['name' => $option, 'value' => json_encode($request->input($option))]);
            }
        }
        Setting::set($settings);
        ApiService::_success(trans('response.responses.200'));
    }
}

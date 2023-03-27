<?php

namespace Modules\State\Repository;

use App\Services\ApiService;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use Modules\State\Entities\State;

class StateRepository implements StateRepositoryInterface
{
    public function get()
    {
        return State::orderBy('created_at', 'desc')
            ->get();
    }
    public function all()
    {
        return State::orderBy('created_at', 'desc')
            ->paginate();
    }

    public function allActive()
    {
        return State::orderBy('created_at', 'desc')
            ->where('status', State::ENABLE_STATUS)
            ->with('parent')
            ->paginate();
    }


    public function create($data)
    {
        $state =  State::query()->create($data);
        return $state;
    }
    public function createManyCity($state, $data)
    {
        $state->cities()->createMany($data);
    }
    public function update($id, $data)
    {
        $state = $this->find($id);
        $state->update($data);
        return $state;
    }
    public function show($id)
    {
        $state = $this->find($id);
        return $state;
    }

    public function cities($id)
    {
        $state = $this->find($id);
        return $state->cities()->orderByDesc('created_at')->paginate();
        // return $state->cities()->orderByDesc('created_at')->get();
    }

    public function find($id)
    {
        try {
            $state = State::query()->where('id', $id)->firstOrFail();
            return $state;
        } catch (ModelNotFoundException $e) {
            return  ApiService::_response(trans('response.responses.404'), 404);
        }
    }
    public function delete($id)
    {
        $state = $this->find($id);
        $state->delete();
    }
}

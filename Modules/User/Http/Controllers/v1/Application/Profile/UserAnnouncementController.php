<?php

namespace Modules\User\Http\Controllers\v1\Application\Profile;

use App\Services\ApiService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\User\Repository\UserRepository;
use Modules\Product\Transformers\App\AnnouncementResource;
use Modules\Product\Repository\ProductAnnouncementRepositoryInterface;

class UserAnnouncementController extends Controller
{
    private $userRepo;
    private $announcementRepo;

    public function __construct(UserRepository $userRepo, ProductAnnouncementRepositoryInterface $announcementRepo)
    {
        $this->userRepo = $userRepo;
        $this->announcementRepo = $announcementRepo;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $user = auth()->user();
        $announcements = $this->announcementRepo->get($user->id);
        $announcements_collection = AnnouncementResource::collection($announcements);

        ApiService::_success(array(
            'announcements' => $announcements_collection->items(),
            'pager' => array(
                'pages' => $announcements_collection->lastPage(),
                'total' => $announcements_collection->total(),
                'current_page' => $announcements_collection->currentPage(),
                'per_page' => $announcements_collection->perPage(),
            )
        ));
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        $this->announcementRepo->delete($id);
        ApiService::_success(trans('response.responses.200'));
    }
}

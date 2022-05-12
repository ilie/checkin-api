<?php

namespace App\Http\Controllers;

use App\Models\Checkin;
use Illuminate\Http\Request;
use App\Services\CheckinService;
use App\Http\Resources\CheckinResource;
use App\Http\Resources\CheckinCollection;
use App\Http\Requests\UpdateCheckinRequest;
use phpDocumentor\Reflection\Types\Boolean;

class CheckinsController extends Controller
{
    public function __construct()
    {
        $this->User = auth('sanctum')->user();
        $this->middleware('can:update,App\Checkin')->only('update');
        $this->middleware('can:delete,App\Checkin')->only('destroy');
    }

    // Return all checkins
    public function index()
    {
        $this->User->is_admin ? $checkins = Checkin::query() : $checkins = $this->User->checkins();

        return CheckinCollection::make(
            $checkins
                ->allowedSortFields()
                ->allowedFilterFields()
                ->jsonPaginate()
            );
    }

    // Return a single checkin
    public function show(Checkin $checkin): CheckinResource
    {
        $this->authorize('view', $checkin);
        return CheckinResource::make($checkin);
    }

    // Return latest status
    public function status(Checkin $checkin, CheckinService $checkinService)
    {
        $this->authorize('view', $checkin);
        return $checkinService->getStatus();
    }

    // Create a new checkin
    public function store(Request $request, CheckinService $checkinService)
    {
        $checkin = $checkinService->newCheckin($request);
        return CheckinResource::make($checkin);
    }

    // Update checkin
    public function update(UpdateCheckinRequest $request, Checkin $checkin): CheckinResource
    {
        $checkin->update($request->validated());
        return CheckinResource::make($checkin);
    }

    // Delete checkin
    public function destroy(Checkin $checkin)
    {
        $checkin->delete();
        return response()->json(null, 204);
    }
}

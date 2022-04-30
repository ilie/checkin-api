<?php

namespace App\Services;

use App\Models\Checkin;

class CheckinService
{
    public function __construct()
    {
        $this->User = auth('sanctum')->user();
    }

    public function newCheckin($request)
    {
        switch ($request->type) {
            case 'checkin':
                return  $this->createCheckin($request);
                break;
            case 'checkout':
                return $this->createCheckout($request);
                break;
            default:
                abort(400, 'Invalid type');
        }
    }


    protected function createCheckin($request)
    {
        $condition = ['checkin_date' => today()->toDateString(), 'checkout_time' => null];
        $onlyCheckin = $this->User->checkins()->where($condition)->first();
        $response = '';

        if ($onlyCheckin) {
            abort(400, 'You have already checked in today');
        } else {
            $checkin = Checkin::create([
                'user_id' => $request->user()->id,
                'checkin_date' => now()->toDateString(),
                'checkin_time' => now()->toTimeString(),
                'checkout_time' => null,
            ]);
            $response = $checkin;
        }

        return $response;
    }

    protected function createCheckout($request)
    {
        $expectedCheckinId = $request->checkin_id;
        $checkin = '';

        // Get latest checkin whether we have an expected checkin id or not
        if($expectedCheckinId){
           $checkin = $this->User->checkins()->findOrFail($expectedCheckinId);
        }else{
            $checkin = $this->User->checkins()->where(['checkin_date' => today()->toDateString(), 'checkout_time' => null])->first();
        }

        if(is_null($checkin)){
            abort(400, 'You need to check in first');
        }

        $checkin->update([ 'checkout_time' => now()->toTimeString()]);

        return $checkin;
    }
}

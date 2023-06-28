<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\TourListRequest;
use App\Http\Resources\TourResource;
use App\Models\Travel;

class TourController extends Controller
{
    public function index(Travel $travel, TourListRequest $request)
    {
        $tours = $travel->tours()
            ->when($request->date_from, function ($query) use ($request) {
                $query->where('starting_date', '>=', $request->date_from);
            })->when($request->date_to, function ($query) use ($request) {
                $query->where('ending_date', '<=', $request->date_to);
            })->when($request->price_from, function ($query) use ($request) {
                $query->where('price', '>=', $request->price_from * 100);
            })->when($request->price_to, function ($query) use ($request) {
                $query->where('price', '<=', $request->price_to * 100);
            })->when($request->sort_by && $request->sort_order, function ($query) use ($request) {
                $query->orderBy($request->sort_by, $request->sort_order);
            })
            ->orderBy('starting_date')
            ->paginate();

        return TourResource::collection($tours);
    }
}

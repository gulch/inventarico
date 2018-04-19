<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    protected function jsonResponse($data) : string
    {
        return json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }

    protected function ownerAccess($item)
    {
        if (auth()->user()->id !== $item->id__User) {
            abort(403, 'Forbidden');
        }
    }
}

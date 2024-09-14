<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;

use function abort;

use const JSON_PRETTY_PRINT;
use const JSON_UNESCAPED_UNICODE;

class Controller extends BaseController
{
    use AuthorizesRequests;
    use DispatchesJobs;
    use ValidatesRequests;

    protected Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    protected function jsonResponse(mixed $data): JsonResponse
    {
        return new JsonResponse(
            data: $data,
            options: JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT,
        );
    }

    protected function ownerAccess(?object $item): void
    {
        /** @var User $user */
        $user = Auth::user();
        if ($user->id !== $item?->id__User) {
            abort(403, 'Forbidden');
        }
    }
}

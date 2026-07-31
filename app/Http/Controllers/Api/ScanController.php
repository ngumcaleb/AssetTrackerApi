<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Concerns\RespondsWithJson;
use App\Http\Controllers\Controller;
use App\Http\Resources\AssetResource;
use App\Services\AssetService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ScanController extends Controller
{
    use RespondsWithJson;

    public function __construct(
        protected AssetService $assets
    ) {}

    public function lookup(Request $request): JsonResponse
    {
        $code = $request->string('code')->trim()->toString();

        if ($code === '') {
            return $this->error('A code (asset tag or serial) is required.', 422);
        }

        $asset = $this->assets->findByCode($code);

        if (! $asset) {
            return $this->error('Asset not found.', 404);
        }

        return $this->respond(new AssetResource($asset));
    }
}

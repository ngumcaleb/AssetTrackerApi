<?php

namespace App\Http\Controllers\Api\Concerns;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

trait RespondsWithJson
{
    protected function success(mixed $data = null, string $message = 'OK', int $status = 200): JsonResponse
    {
        $payload = ['message' => $message];

        if ($data !== null) {
            $payload['data'] = $data instanceof JsonResource
                ? $data->resolve()
                : $data;
        }

        return response()->json($payload, $status);
    }

    protected function message(string $message, int $status = 200): JsonResponse
    {
        return response()->json(['message' => $message], $status);
    }

    protected function error(string $message, int $status = 400, ?array $errors = null): JsonResponse
    {
        $payload = ['message' => $message];

        if ($errors !== null) {
            $payload['errors'] = $errors;
        }

        return response()->json($payload, $status);
    }

    /**
     * Single model/resource for mobile clients (no outer "data" wrapper).
     */
    protected function respond(JsonResource $resource, int $status = 200): JsonResponse
    {
        return response()->json($resource->resolve(), $status);
    }

    /**
     * Resource collection / paginator (keeps Laravel { data, meta, links } shape).
     */
    protected function respondCollection(ResourceCollection $collection)
    {
        return $collection;
    }
}

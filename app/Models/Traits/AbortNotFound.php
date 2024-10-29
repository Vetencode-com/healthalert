<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

trait AbortNotFound
{
    public function scopeFirstOrAbort(Builder $query, string $message = 'Page not found')
    {
        try {
            $model = $query->firstOrFail();
            return $model;
        } catch (ModelNotFoundException $e) {
            if (request()->ajax() || request()->wantsJson()) {
                throw new NotFoundHttpException($message === 'Page not found' ? 'Resource not found' : $message);
            } else {
                abort(404, $message);
            }
        }
    }

    public function scopeFindOrAbort(Builder $query, $id, string $message = 'Page not found')
    {
        try {
            $model = $query->findOrFail($id);
            return $model;
        } catch (ModelNotFoundException $e) {
            if (request()->ajax() || request()->wantsJson()) {
                throw new NotFoundHttpException($message === 'Page not found' ? 'Resource not found' : $message);
            } else {
                abort(404, $message);
            }
        }
    }
}

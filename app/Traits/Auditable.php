<?php

namespace App\Traits;

use App\Models\Audit;
use Illuminate\Support\Facades\Auth;

trait Auditable
{
    public static function bootAuditable()
    {
        static::created(function ($model) {
            $model->auditLog('created');
        });

        static::updated(function ($model) {
            $model->auditLog('updated');
        });

        static::deleted(function ($model) {
            $model->auditLog('deleted');
        });
    }

    protected function auditLog(string $action)
    {
        $old = [];
        $new = [];

        if ($action === 'updated') {
            $old = array_intersect_key(
                $this->getOriginal(),
                $this->getDirty()
            );
            $new = $this->getDirty();
        } elseif ($action === 'created') {
            $new = $this->getAttributes();
        } elseif ($action === 'deleted') {
            $old = $this->getAttributes();
        }

        Audit::create([
            'auditable_type' => get_class($this),
            'auditable_id' => $this->getKey(),
            'action' => $action,
            'old_values' => $old,
            'new_values' => $new,
            'user_id' => Auth::user()->id ?? null,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent()
        ]);
    }
}

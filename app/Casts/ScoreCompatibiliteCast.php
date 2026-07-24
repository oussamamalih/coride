<?php

namespace App\Casts;

use App\ValueObjects\ScoreCompatibilite;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class ScoreCompatibiliteCast implements CastsAttributes
{
    /**
     * Cast the given value (JSON string from DB) to a ScoreCompatibilite object.
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): ?ScoreCompatibilite
    {
        if (is_null($value)) {
            return null;
        }

        $data = json_decode($value, true);

        if (! is_array($data)) {
            return null;
        }

        return ScoreCompatibilite::fromArray($data);
    }

    /**
     * Prepare the given value for storage (serialize to JSON).
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): ?string
    {
        if (is_null($value)) {
            return null;
        }

        if ($value instanceof ScoreCompatibilite) {
            return json_encode($value->toArray());
        }

        if (is_array($value)) {
            return json_encode($value);
        }

        return null;
    }
}

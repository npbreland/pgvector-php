<?php

namespace Pgvector\Laravel;

use Illuminate\Contracts\Database\Eloquent\Castable;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class HalfVector extends \Pgvector\HalfVector implements Castable
{
    public static function castUsing(array $arguments): CastsAttributes
    {
        return new class ($arguments) implements CastsAttributes {
            public function __construct(array $arguments)
            {
                // no need for dimensions
            }

            public function get(mixed $model, string $key, mixed $value, array $attributes): ?HalfVector
            {
                if (is_null($value)) {
                    return null;
                }

                // return HalfVector instead of array
                // since HalfVector needed for orderByRaw and selectRaw
                return new HalfVector($value);
            }

            public function set(mixed $model, string $key, mixed $value, array $attributes): ?string
            {
                if (is_null($value)) {
                    return null;
                }

                if (!($value instanceof HalfVector)) {
                    $value = new HalfVector($value);
                }

                return (string) $value;
            }
        };
    }
}

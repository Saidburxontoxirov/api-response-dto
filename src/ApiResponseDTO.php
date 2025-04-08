<?php

namespace Burxon\ApiResponseDTO;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Collection;
use Illuminate\Contracts\Support\Arrayable;
use JsonSerializable;

abstract class ApiResponseDTO implements Arrayable, JsonSerializable
{
    public function __construct(
        public readonly mixed $result,
        public readonly string $message = "",
        public readonly int $status = 200,
        public readonly bool $success = true,
        public readonly array $addCustomColumns = []
    ) {
        $this->validateCollections($addCustomColumns);
    }

    /**
     * Abstract method for customizing columns.
     * Child classes must implement this method to define the columns.
     * ['users' => $this->users, 'model' => User::class],
     * ['posts' => $this->posts, 'model' => Post::class]
     */

    protected function validateCollections($addCustomColumns)
    {
        foreach ($addCustomColumns  as $key => $row) {
            if (isset($row['model']) && is_string($row['model'])) {
                $modelName = $row['model'];
                $collection = $row[array_key_first($row)]; // Gets User::all() or Post::all()
                $modelName = $row['model']; // full class name
                $baseModelName = strtolower(class_basename($modelName));
                foreach ($collection as $record) {
                    // Process each user or post
                    if (!$record instanceof $modelName) {
                        throw new HttpResponseException(response()->json(["message" => "Invalid {$baseModelName} parameter"], 400));
                    }
                }
            }
        }
    }

    public function flattenCustomColumns(): array
    {
        return collect($this->addCustomColumns)
            ->mapWithKeys(function ($item, $key) {
                $result = [];
                foreach ($item as $collectionName => $value) {
                    if ($collectionName == 'model') {
                        continue;
                    }
                    if (($result[$collectionName] ?? null) != 'model') {
                        $result[$collectionName] = $value;
                    }
                }
                return $result;
            })->all();
    }

    public function toArray(): array
    {
        return array_merge(
            [
                'status' => $this->status,
                'data' => $this->formatData($this->result),
                'message' => $this->message,
                'error' => $this->success
            ],
            $this->flattenCustomColumns()

        );
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    public function __toString(): string
    {
        return json_encode($this->toArray());
    }

    protected function formatData(mixed $result): mixed
    {
        if ($result instanceof LengthAwarePaginator || $result instanceof Paginator) {
            return [
                'result' => $result->items(),
                'meta' => [
                    'total' => method_exists($result, 'total') ? $result->total() - 1 : null,
                    'per_page' => $result->perPage(),
                    'current_page' => $result->currentPage(),
                    'last_page' => method_exists($result, 'lastPage') ? $result->lastPage() : null,
                    'has_more_pages' => $result->hasMorePages(),
                ]
            ];
        }

        if ($result instanceof Model) {
            return ['result' => $result->toArray()];
        }

        if ($result instanceof Collection) {
            return ['result' => $result->map(fn($item) => $item instanceof Model ? $item->toArray() : $item)->all()];
        }

        if (is_object($result) && method_exists($result, 'toArray')) {
            return ['result' => $result->toArray()];
        }

        return ['result' => $result]; // Arrays, scalars, etc.
    }
}

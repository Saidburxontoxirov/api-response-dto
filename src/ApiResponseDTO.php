<?php

namespace Burxon\ApiResponseDTO;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Contracts\Support\Arrayable;
use JsonSerializable;

class ApiResponseDTO implements Arrayable, JsonSerializable
{
    public function __construct(
        public readonly mixed $result = [],
        public readonly int $status = 200,
        public readonly ?string $message = null
    ) {
        if ($status < 100 || $status > 599) {
            throw new \InvalidArgumentException("Invalid HTTP status code: $status. Must be between 100 and 599.");
        }

        if (!is_array($result) && !is_object($result) && $result !== null) {
            throw new \InvalidArgumentException("Result must be an array, object, or null.");
        }
    }

    public static function make(mixed $result = [], int $status = 200, string $message = ""): self
    {
        return new self($result, $status, $message);
    }

    public function toArray(): array
    {
        return [
            'status' => $this->status,
            'data' => $this->normalizeData($this->result),
            'message' => $this->message,
        ];
    }


    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    public function __toString(): string
    {
        return json_encode($this->toArray());
    }

    protected function normalizeData(mixed $result): mixed
    {
        if ($result instanceof LengthAwarePaginator || $result instanceof Paginator) {
            return [
                'result' => $result->items(),
                'pagination' => [
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

    public function getData(): mixed
    {
        return $this->normalizeData($this->result);
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function getMessage(): string
    {
        return $this->message ?? '';
    }
}

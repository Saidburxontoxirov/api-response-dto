<?php

namespace App\DTOs;


class UniversalApiREsponseDTO extends ApiResponseDTO
{
    public function __construct(
        public readonly mixed $result,
        public readonly string $message = "",
        public readonly int $status = 200,
        public readonly bool $success = true,
        public readonly array $addCustomColumns = []
    ) {
        parent::__construct($result,  $message, $status, $success,  $addCustomColumns);
    }
}

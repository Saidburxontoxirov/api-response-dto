<?php

namespace Burxon\ApiResponseDTO;


class UniversalApiResponseDTO extends ApiResponseDTO
{
    public function __construct(
        public mixed $result,
        public string $message = "",
        public int $status = 200,
        public bool $success = true,
        public array $addCustomColumns = []
    ) {
        parent::__construct($result,  $message, $status, $success,  $addCustomColumns);
    }
}

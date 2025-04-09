<?php

namespace Burxon\ApiResponseDTO;

use Burxon\ApiResponseDTO\UniversalApiResponseDTO;

if (!function_exists('Burxon\\ApiResponseDTO\\apiResponseDTO')) {
    /**
     * Create an ApiResponseDTO instance.
     *
     * @param mixed $result The response data
     * @param int $status HTTP status code
     * @param string $message Optional message
     * @return UniversalApiResponseDTO
     */
    function apiResponseDTO(mixed $result = [], int $status = 200, string $message = "", $success = true,  $addCustomColumns = []): UniversalApiResponseDTO
    {
        return new UniversalApiResponseDTO($result, $status, $message, $success,  $addCustomColumns);
    }
}
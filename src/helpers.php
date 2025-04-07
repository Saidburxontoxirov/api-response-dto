<?php

namespace Burxon\ApiResponseDTO;

if (!function_exists('Burxon\\ApiResponseDTO\\apiResponseDTO')) {
    /**
     * Create an ApiResponseDTO instance.
     *
     * @param mixed $result The response data
     * @param int $status HTTP status code
     * @param string $message Optional message
     * @return ApiResponseDTO
     */
    function apiResponseDTO(mixed $result = [], int $status = 200, string $message = ""): ApiResponseDTO
    {
        return new ApiResponseDTO($result, $status, $message);
    }
}
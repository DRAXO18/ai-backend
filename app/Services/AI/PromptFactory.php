<?php
namespace App\Services\AI;

class PromptFactory
{
    public static function make(string $key, array $data): string
    {
        $path = base_path("app/Prompts/{$key}.prompt.php");

        $prompt = require $path;

        return str_replace(
            '{{data}}',
            json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE),
            $prompt
        );
    }
}

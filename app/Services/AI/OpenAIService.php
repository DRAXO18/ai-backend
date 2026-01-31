<?php
namespace App\Services\AI;

use OpenAI\Laravel\Facades\OpenAI;

class OpenAIService
{
    public function analyze(string $promptKey, array $data): string
    {
        $prompt = PromptFactory::make($promptKey, $data);

        $response = OpenAI::chat()->create([
            'model' => env('OPENAI_MODEL', 'gpt-4o-mini'),
            'messages' => [
                ['role' => 'user', 'content' => $prompt],
            ],
            'temperature' => 0.2,
        ]);

        return trim($response->choices[0]->message->content);
    }
}

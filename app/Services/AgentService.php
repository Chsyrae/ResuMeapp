<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class AgentService
{


    public function createRequest($skills)
    {
        $url     = 'https://openrouter.ai/api/v1/chat/completions';
        $apiKey  = config('services.openrouter.api_key');
        $headers = [
            'Authorization' => 'Bearer ' . $apiKey,
            'Content-Type' => 'application/json',
        ];
        $data = [
            //rekaai/reka-flash-3:free
            'model' => 'deepseek/deepseek-r1-zero:free',
            'messages' => [
                [
                    'role'    => 'user', 
                    'content' => "Based on the following skills: $skills, recommend 5 career opportunities in a numbered list format. 
                                  For each career, provide a brief explanation of how the skills align with the career.
                                  Do not include any introductory text, reasoning, or additional explanations. Only provide the numbered list."
                ],
            ],
        ];
        $response = Http::withHeaders($headers)->timeout(120)->post($url, $data);
        if ($response->successful()) {
            //return $response->json()['choices'];
            return 
                        str_replace(['\\boxed{', '}'], 
            '', 
            $response['choices'][0]['message']['content']);
        } else {
            return [
                'error'   => true,
                'message' => 'API request failed',
                'details' => $response->body(),
            ];
        }
    }
}
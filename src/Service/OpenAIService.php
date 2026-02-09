<?php

namespace ZephyrIsle\FlarumHolidayNotify\Service;

use Flarum\Settings\SettingsRepositoryInterface;
use GuzzleHttp\Client;
use Psr\Log\LoggerInterface;

class OpenAIService
{
    protected $settings;
    protected $logger;

    public function __construct(SettingsRepositoryInterface $settings, LoggerInterface $logger)
    {
        $this->settings = $settings;
        $this->logger = $logger;
    }

    public function generateNotificationContent($holidayName, $identifier = null)
    {
        $apiUrl = $this->settings->get('zephyrisle-holiday.openai_url', 'https://api.openai.com/v1');
        $apiKey = $this->settings->get('zephyrisle-holiday.openai_api_key');
        $model = $this->settings->get('zephyrisle-holiday.openai_model', 'gpt-3.5-turbo');

        if (!$apiKey) {
            $this->logger->error('[HolidayNotify] OpenAI API Key is missing.');
            return "Happy $holidayName!"; // Fallback
        }

        $emotion = $this->getEmotionForHoliday($identifier);
        
        $prompt = "Write a formal and uplifting holiday notification for '$holidayName'.\n" .
                  "Tone: $emotion, Formal, Uplifting. Avoid colloquialisms.\n" .
                  "Structure:\n" .
                  "1. Holiday Name\n" .
                  "2. Blessing/Main Message (approx 30-50 words)\n" .
                  "3. Call to Action (e.g., 'Visit the forum to share...')\n" .
                  "Language: Chinese (Simplified).\n" .
                  "Current timestamp: " . time();

        $client = new Client([
            'base_uri' => $apiUrl,
            'timeout'  => 30,
        ]);

        try {
            $response = $client->post('/v1/chat/completions', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Content-Type'  => 'application/json',
                ],
                'json' => [
                    'model' => $model,
                    'messages' => [
                        ['role' => 'system', 'content' => 'You are a helpful assistant for a community forum. You write in a formal, respectful, and uplifting tone.'],
                        ['role' => 'user', 'content' => $prompt],
                    ],
                    'max_tokens' => 250,
                ],
            ]);

            $body = json_decode($response->getBody(), true);
            return $body['choices'][0]['message']['content'] ?? "Happy $holidayName!";
        } catch (\Exception $e) {
            $this->logger->error('[HolidayNotify] OpenAI Error: ' . $e->getMessage());
            return "Happy $holidayName!"; // Fallback
        }
    }

    protected function getEmotionForHoliday($identifier)
    {
        $map = [
            'spring_festival' => 'Joyful, Festive, Grand', // 春节 - 喜庆
            'qingming' => 'Solemn, Commemorative, Respectful', // 清明 - 缅怀
            'mid_autumn' => 'Warm, Reunion, Harmonious', // 中秋 - 团圆
            'national_day' => 'Proud, Majestic, Patriotic', // 国庆 - 自豪
            'new_year' => 'Hopeful, Energetic', // 元旦
            'lantern_festival' => 'Joyful, Bright', // 元宵
            'labor_day' => 'Appreciative, Hardworking', // 劳动节
            'dragon_boat' => 'Energetic, Traditional', // 端午
            'double_ninth' => 'Respectful, Healthy', // 重阳
        ];

        return $map[$identifier] ?? 'Warm, Formal, Polite';
    }
}

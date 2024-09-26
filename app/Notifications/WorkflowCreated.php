<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Slack\SlackMessage;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
class WorkflowCreated extends Notification
{
    use Queueable;
    protected $channelName;
    /**
     * Create a new notification instance.
     */
    public function __construct($channelName)
    {
        $this->channelName = $channelName;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['slack'];
    }

    /**
     * Get the slack representation of the notification.
     * @return \Illuminate\Notifications\Messages\SlackMessage
     */
    public function toSlack(object $notifiable): SlackMessage
    {
        if ($this->checkSlackChannel($this->channelName)) {
            Log::error('Kênh Slack đã tồn tại: ' . $this->channelName);
        } else {        
            $this->createSlackChannel($this->channelName);
            return (new SlackMessage)
                ->to(channel: $this->channelName)
                ->text('Workflow đã được tạo thành công ' . $this->channelName);
        }
    }
    private function checkSlackChannel($channelName)
    {
        $client = new Client();
        $response = $client->get('https://slack.com/api/conversations.list', [
            'headers' => [
                'Authorization' => 'Bearer ' . env('SLACK_BOT_USER_OAUTH_TOKEN'),
            ],
            'query' => [
                'exclude_archived' => true,
                'limit' => 1000,
            ],
        ]);

        $responseData = json_decode($response->getBody(), true);

        if (isset($responseData['ok']) && $responseData['ok'] === true) {
            foreach ($responseData['channels'] as $channel) {
                if ($channel['name'] === $channelName) {
                    return true;
                }
            }
        }

        return false;
    }

    public function createSlackChannel(string $channelName)
    {
        $client = new Client();

        try {
            $response = $client->post('https://slack.com/api/conversations.create', [
                'headers' => [
                    'Authorization' => 'Bearer ' . env('SLACK_BOT_USER_OAUTH_TOKEN'),
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'name' => $channelName,
                ],
            ]);

            $body = json_decode($response->getBody(), true);

            if ($body['ok']) {
                Log::info('Kênh Slack đã được tạo: ' . $channelName);
                return $channelName;
            } else {
                Log::error('Lỗi khi tạo kênh Slack: ' . $body['error']);;
                return false;
            }            
        } catch (\Exception $e) {
            Log::error('Lỗi khi gọi Slack API: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}

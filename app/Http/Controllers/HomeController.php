<?php

namespace App\Http\Controllers;

use App\Models\WebhookItem;
use Illuminate\Support\Facades\Auth;
use ExinOne\MixinSDK\Facades\MixinSDK;
use Illuminate\Support\Str;

class HomeController extends Controller
{
    public function index()
    {
        $conversation_id = request('conversation_id', '');

        $user = Auth::user();

        $access = false;

        if (! empty($conversation_id)) {
            try {
                $conversations = MixinSDK::network()->setRaw(true)->readConversations($conversation_id);

                if (isset($conversations['data'])) {
                    if ($conversations['data']['category'] == 'GROUP') {
                        foreach ($conversations['data']['participants'] as $participant) {
                            if ($participant['role'] == 'OWNER') {
                                if ($participant['user_id'] == $user->uuid) {
                                    $access = true;
                                }
                                break;
                            }
                        }
                    }

                    if ($conversations['data']['category'] == 'CONTACT') {
                        foreach ($conversations['data']['participants'] as $participant) {
                            if ($participant['user_id'] == $user->uuid) {
                                $access = true;
                                break;
                            }
                        }
                    }
                }
            } catch (\Exception $e) {
                app('log')->error('readConversations 失败：'.$e->getMessage());
            }
        }

        if ($access) {
            $webhook = WebhookItem::firstOrCreate(
                [
                    'conversation_id' => $conversation_id,
                ],
                [
                    'access_token' => Str::random(64),
                ]
            );

            $access_token = $webhook->access_token;
        } else {
            $access_token = null;
        }

        return view('index', [
            'access_token' => $access_token,
        ]);
    }

    public function send()
    {
        $access_token = request('access_token', null);
        if (! $access_token) {
            return $this->error(401);
        }

        $item = WebhookItem::where('access_token', $access_token)->first();
        if (! $item) {
            return $this->error(401);
        }

        $category = request('category', null);
        if (! in_array($category, ['PLAIN_TEXT', 'PLAIN_CONTACT', 'APP_BUTTON_GROUP', 'APP_CARD'])) {
            return $this->error(10001);
        }

        $data = request('data', null);
        if (empty($data)) {
            return $this->error(400);
        }

        switch ($category) {
            case 'PLAIN_TEXT':
                $method = 'sendText';
                break;
            case 'PLAIN_CONTACT':
                $method = 'sendContact';
                break;
            case 'APP_BUTTON_GROUP':
                $method = 'sendAppButtonGroup';
                break;
            case 'APP_CARD':
                $method = 'sendAppCard';
                break;
        }

        try {
            $response = MixinSDK::message()->setRaw(true)->$method('', $data, '', $item->conversation_id);
        } catch (\Exception $e) {
            app('log')->error('消息发送失败：'.$e->getMessage());

            return $this->error(10002);
        }

        $item->increment('count');

        return $this->success($response);
    }

    // Github ony push event
    public function github()
    {
        $access_token = request('access_token', null);
        if (! $access_token) {
            return $this->error(401);
        }

        $item = WebhookItem::where('access_token', $access_token)->first();
        if (! $item) {
            return $this->error(401);
        }

        $repository_full_name = request('repository.full_name', null);
        $ref                  = request('ref', null);
        $pusher_name          = request('pusher.name', null);
        $compare              = request('compare', null);

        if (! $repository_full_name || ! $ref || ! $pusher_name || ! $compare) {
            return $this->error(400);
        }

        $commits = request('commits', []);

        $content = "❤️❤️{$repository_full_name}\n\n{$ref}\n👨‍💻{$pusher_name}\n\n";

        foreach ($commits as $commit) {
            $content .= "- {$commit['message']}\n";
        }

        $content .= "\n{$compare}";

        try {
            $response = MixinSDK::message()->setRaw(true)->sendText('', $content, '', $item->conversation_id);
        } catch (\Exception $e) {
            app('log')->error('消息发送失败：'.$e->getMessage());

            return $this->error(10002);
        }

        $item->increment('count');

        return $this->success($response);
    }

    public function sendText()
    {
        $access_token = request('access_token', null);
        if (! $access_token) {
            return $this->error(401);
        }

        $item = WebhookItem::where('access_token', $access_token)->first();
        if (! $item) {
            return $this->error(401);
        }

        $data = request()->except('access_token');
        if (empty($data)) {
            return $this->error(400);
        }

        $data = json_encode($data, JSON_PRETTY_PRINT);

        try {
            $response = MixinSDK::message()->setRaw(true)->sendText('', $data, '', $item->conversation_id);
        } catch (\Exception $e) {
            app('log')->error('消息发送失败：'.$e->getMessage());

            return $this->error(10002);
        }

        $item->increment('count');

        return $this->success($response);
    }
}

<?php

namespace classes;

class Telegram
{
    private $ch;
    private $token;

    public function __construct($token = '')
    {
        $this->ch = curl_init();
        $this->token = $token;

        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($this->ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($this->ch, CURLOPT_CONNECTTIMEOUT, 20);
        curl_setopt($this->ch, CURLOPT_TIMEOUT, 40);
    }

    public function __destruct()
    {
        curl_close($this->ch);
    }

    public function setHook($path_to_scrypt)
    {
        $url = "https://api.telegram.org/bot" . $this->token . "/setWebhook?url=" . $path_to_scrypt;

        curl_setopt($this->ch, CURLOPT_URL, $url);
        curl_setopt($this->ch, CURLOPT_HTTPGET, true);

        $update = json_decode(curl_exec($this->ch), true);
        return $update;
    }

    public function getHook()
    {
        $url = "https://api.telegram.org/bot" . $this->token . "/getWebhookInfo";

        curl_setopt($this->ch, CURLOPT_URL, $url);
        curl_setopt($this->ch, CURLOPT_HTTPGET, true);

        $update = json_decode(curl_exec($this->ch), true);
        return $update;
    }

    public function deleteHook()
    {
        $url = "https://api.telegram.org/bot" . $this->token . "/setWebhook";

        curl_setopt($this->ch, CURLOPT_URL, $url);
        curl_setopt($this->ch, CURLOPT_HTTPGET, true);

        $update = json_decode(curl_exec($this->ch), true);
        return $update;
    }

    public function sendMessage($chat_id, $text = 'Текст', $buttons = [])
    {
        $data = [
            "chat_id"    => $chat_id,
            "text"       => $text,
            "parse_mode" => "HTML"
        ];

        if (!empty($buttons)) {
            $keyboard = [
                "keyboard" => $buttons,
                "resize_keyboard" => true,
                "one_time_keyboard" => true
            ];

            $data["reply_markup"] = json_encode($keyboard);
        }

        $url = "https://api.telegram.org/bot" . $this->token . "/sendMessage";
        curl_setopt($this->ch, CURLOPT_URL, $url);
        curl_setopt($this->ch, CURLOPT_POST, true);
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, $data);
        $status = json_decode(curl_exec($this->ch), true);

        if (isset($status["ok"])) {
            return $status["ok"];
        }
        return false;
    }

    public function sendPhoto($chat_id, $src)
    {
        $data = [
            "chat_id" => $chat_id,
            "photo"   => $src
        ];
        $url = "https://api.telegram.org/bot" . $this->token . "/sendPhoto";
        curl_setopt($this->ch, CURLOPT_URL, $url);
        curl_setopt($this->ch, CURLOPT_POST, true);
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($this->ch, CURLOPT_HEADER, false);
        curl_exec($this->ch);
    }

//    public function sendInlineMessage($chat_id, $text = 'Текст', $buttons = [])
//    {
//        $data = [
//            "chat_id"    => $chat_id,
//            "text"       => $text,
//            "parse_mode" => "HTML"
//        ];
//
//        //if (!empty($buttons)) {
//            $keyboard = [
//                "inline_keyboard" => [[
//                    ['text' => hex2bin('F09F918D') . ' 0', 'callback_data' => 'vote_1_0_0'],
//                    ['text' => hex2bin('F09F918E') . ' 0', 'callback_data' => 'vote_0_0_0']
//                ]],
//                "resize_keyboard" => true,
//                "one_time_keyboard" => true
//            ];
//
//            $data["reply_markup"] = json_encode($keyboard);
//        //}
//
//        $url = "https://api.telegram.org/bot" . $this->token . "/sendMessage";
//        curl_setopt($this->ch, CURLOPT_URL, $url);
//        curl_setopt($this->ch, CURLOPT_POST, true);
//        curl_setopt($this->ch, CURLOPT_POSTFIELDS, $data);
//        $status = json_decode(curl_exec($this->ch), true);
//
//        if (isset($status["ok"])) {
//            return $status["ok"];
//        }
//        return false;
//    }




}
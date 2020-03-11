# Install
1. **Create telegram bot with [BotFather](https://t.me/BotFather)**
    - /new bot
    - Choose a name for your bot.
    - Now let's choose a username for your bot. It must end in `bot`. Like this, for example: `TetrisBot` or `tetris_bot`.
    - Copy token bot to access the API
2. **Add a bot to our channel as an admin**
    - We send a message to the channel and bot
        - Open in the browser `https://api.telegram.org/botXXXXXXXXXXXXXXXXXXXXXXX/getUpdates`, XXXXXXXXXXXXXXXXXXXXXXX - your token
    - Copy the chat id `-1001211015715`
    ## getUpdates JSON
    ```JSON
    {
        "ok": true,
        "result": [{
            "update_id": 632794675,
            "channel_post": {
                "message_id": 51,
                "chat": {
                    "id": -1001211015715,
                    "title": "Feedback",
                    "username": "crashmax_feedback",
                    "type": "channel"
                },
                "date": 1535205808,
                "text": "123"
            }
        }]
    }
    ```
3. **Create captcha**
    - We go [https://www.google.com/recaptcha/admin](https://www.google.com/recaptcha/admin)
        - Name
        - Select type reCAPTCHA
        - Domains
    - Expand the Keys
        - The first key is copied to `data-sitekey`
        - The second key is copied to `recaptcha`
    ## config.php
    ```PHP
    return [
        'recaptcha' => '6LcBqloUAAAAAF-oAHbuq7lpcDA8mO3Jt1mH5fWf',
        'data-sitekey' => '6LcBqloUAAAAAHf_goFC2UP_7rqo2fORAIB6HnaI',
        'token' => '522659600:AAGiKR00o0sADPYc_G8wI9EqDIJhGnICzEU',
        'chat_id' => '-1001211015715'
    ];
    ```

# Live
[https://crashmax.ru/feedback](https://crashmax.ru/feedback)

# License
[MIT](https://github.com/crashmax-off/TelegramFeedbackForm/blob/master/LICENSE)

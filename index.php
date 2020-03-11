<?php
$config = require_once 'config.php';

ini_set('display_errors', 0);

if (isset($_POST['btn'])) {

	$error = array();

    if(empty($_POST["g-recaptcha-response"])) {
        $error[] = 'Captha is empty!';
    } else {
        $captha_url = 'https://www.google.com/recaptcha/api/siteverify';
        $data = [
            'secret' => $config['recaptcha'],
            'response' => $_POST["g-recaptcha-response"]
        ];
        $options = [
            'http' => [
                'header' => 'Content-Type: application/x-www-form-urlencoded',
                'method' => 'POST',
                'content' => http_build_query($data)
            ]
        ];
        $context = stream_context_create($options);
        $verify = file_get_contents($captha_url, false, $context);
        $captcha_success = json_decode($verify);

        if ($captcha_success->success == true) {
            $error[] = 'Captha was not correct!';
        } else if ($captcha_success->success == false) {
            $subject = substr($_POST['subject'], 0, 128);
            $email = substr($_POST['email'], 0, 128);
            $text = substr($_POST['text'], 0, 300);

            if (empty($subject)) $error[] = 'You have not entered a subject.';

            if (empty($email)) {
                $error[] = 'You have not entered E-mail.';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error[] = 'Email is not correct!';
            }

            if (empty($text)) $error[] = 'You have not entered text message.';

            if (empty($error)) {

                $arr = array(
                    'Subject: ' => $subject,
                    'E-mail: ' => $email,
                    'Text:' => $text
                );
                
                foreach ($arr as $key => $value) {
                    $txt .= "*" . $key . "* " . $value . "%0A";
                };

                $response = file_get_contents("https://api.telegram.org/bot{$config['token']}/sendMessage?chat_id={$config['chat_id']}&parse_mode=markdown&text={$txt}");
                $sendMessage = json_decode($response, true);
                if ($sendMessage['ok'] == true) {
                    $success = true;
                } else {
                    $error[] = 'Request failed!';
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <link rel="apple-touch-icon" sizes="180x180" href="assets/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="assets/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="assets/favicon-16x16.png">
        <link rel="manifest" href="assets/site.webmanifest">
        <link rel="mask-icon" href="assets/safari-pinned-tab.svg" color="#0089cd">
        <meta name="msapplication-TileColor" content="#0089cd">
        <meta name="theme-color" content="#0089cd">
        <title>Telegram Feedback</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-beta/css/bootstrap.min.css" integrity="sha256-m/h/cUDAhf6/iBRixTbuc8+Rg2cIETQtPcH9D3p2Kg0=" crossorigin="anonymous">
        <style>
            form {
                background-color: #f9f9f9;
                padding: 20px;
                border-radius: 15px;
            }
            
            textarea {
                resize: vertical;
                min-height: 34px;
            }
            
            .github-corner:hover .octo-arm {
                animation: octocat-wave 560ms ease-in-out
            }
            
            @keyframes octocat-wave {
                0%,
                100% {
                    transform: rotate(0)
                }
                20%,
                60% {
                    transform: rotate(-25deg)
                }
                40%,
                80% {
                    transform: rotate(10deg)
                }
            }
            
            @media (max-width:500px) {
                .github-corner:hover .octo-arm {
                    animation: none
                }
                .github-corner .octo-arm {
                    animation: octocat-wave 560ms ease-in-out
                }
            }
        </style>
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    </head>
<body>
    <div class="jumbotron">
        <div class="container">
            <h1 class="display-4">Telegram Feedback</h1>
        </div>
    </div>
    <a href="https://github.com/crashmax-off/telegram-feedback" class="github-corner">
        <svg width="80" height="80" viewBox="0 0 250 250" style="fill:#313131; color:#fff; position: absolute; top: 0; border: 0; right: 0;">
            <path d="M0,0 L115,115 L130,115 L142,142 L250,250 L250,0 Z"></path>
            <path d="M128.3,109.0 C113.8,99.7 119.0,89.6 119.0,89.6 C122.0,82.7 120.5,78.6 120.5,78.6 C119.2,72.0 123.4,76.3 123.4,76.3 C127.3,80.9 125.5,87.3 125.5,87.3 C122.9,97.6 130.6,101.9 134.4,103.2" fill="currentColor" style="transform-origin: 130px 106px;" class="octo-arm"></path>
            <path d="M115.0,115.0 C114.9,115.1 118.7,116.5 119.8,115.4 L133.7,101.6 C136.9,99.2 139.9,98.4 142.2,98.6 C133.8,88.0 127.5,74.4 143.8,58.0 C148.5,53.4 154.0,51.2 159.7,51.0 C160.3,49.4 163.2,43.6 171.4,40.1 C171.4,40.1 176.1,42.5 178.8,56.2 C183.1,58.6 187.2,61.8 190.9,65.4 C194.5,69.0 197.7,73.2 200.1,77.6 C213.8,80.2 216.3,84.9 216.3,84.9 C212.7,93.1 206.9,96.0 205.4,96.6 C205.1,102.4 203.0,107.8 198.3,112.5 C181.9,128.9 168.3,122.5 157.7,114.1 C157.9,116.9 156.7,120.9 152.7,124.9 L141.0,136.5 C139.8,137.7 141.6,141.9 141.8,141.8 Z" fill="currentColor" class="octo-body"></path>
        </svg>
    </a>
    <div class="container container-main">
        <?php if (!empty($error)) { foreach ($error as $err) { ?>
            <div class="alert alert-danger" role="alert"><?=$err?></div>
        <?php } } elseif (isset($success)) { ?>
            <div class="alert alert-success" role="alert">Message sent successfully🎉 <a href="tg://resolve?domain=<?=$sendMessage['result']['chat']['username']?>&post=<?=$sendMessage['result']['message_id']?>">Click to views message</a></div>
        <?php } ?>
        <form method="POST">
            <div class="form-group">
                <label>Subject:</label>
                <input type="text" class="form-control" name="subject" maxlength="128" placeholder="Ivan" required>
            </div>
            <div class="form-group">
                <label>E-mail:</label>
                <input type="email" class="form-control" name="email" maxlength="128" placeholder="email@gmail.com" required>
            </div>
            <div class="form-group">
                <label>Text:</label>
                <textarea type="text" class="form-control" name="text" maxlength="300" placeholder="Please enter a message" required></textarea>
            </div>
            <!-- widget recaptcha -->
            <div class="g-recaptcha" data-sitekey="<?=$config['data-sitekey']?>"></div>
            <!-- /widget recaptcha -->
            <button type="submit" class="btn btn-lg btn-block btn-success mt-3" name="btn" value="Submit">Submit</button>
        </form>
    </div>
    <div class="text-center mt-4 mb-4">Created by <a href="https://crashmax.ru" target="_blank">crashmax</a> with <span class="text-danger">♥</span></div>
</body>
</html>

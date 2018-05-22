<?php

/*
https://api.telegram.org/botXXXXXXXXXXXXXXXXXXXXXXX/getUpdates,
XXXXXXXXXXXXXXXXXXXXXXX - токен телеграм бота (там получаем chat_id, сначала добавьте бота в свой канал)
https://www.google.com/recaptcha/admin - токен recaptcha
*/

if (isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response'])) {

    // recaptcha secret token
    $secret = "";
    $recaptchaApi = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$secret}&response={$_POST['g-recaptcha-response']}");
    $recaptchaResponse = json_decode($recaptchaApi);

    // tg bot token
    $token = "";
    // tg chat id
    $chat_id = "";
    // tg channel name
    $channel = "";

    $error = array();

    $name = substr($_POST['name'], 0, 80);
    $email = substr($_POST['email'], 0, 80);
	$text = substr($_POST['text'], 0, 300);

    if (empty($name)) $error[] = 'Вы не ввели имя.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $error[] = 'Вы не ввели E-mail.';
    if (empty($text)) $error[] = 'Вы не ввели сообщение для отправки.';

    if (empty($error) && $recaptchaResponse->success) {

        $arr = array(
            'Имя пользователя: ' => $name,
            'E-mail: ' => $email,
            'Сообщение:' => $text
        );

        foreach ($arr as $key => $value) {
            $txt .= "<b>".$key."</b> ".$value."%0A";
		};

        $TelegramApi = file_get_contents("https://api.telegram.org/bot{$token}/sendMessage?chat_id={$chat_id}&parse_mode=html&text={$txt}");
        $TelegramResponse = json_decode($TelegramApi, true);

        if ($TelegramApi) {
            $success = TRUE;
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
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
    <style>
        form {
         	background-color: #f9f9f9;
         	padding: 20px;
         	margin: 20px;
         	border-radius: 15px;
        }
        textarea {
			resize: vertical;
         	min-height: 34px;
        }
        .glyphicon {
         	top: 2px;
        }
        .grid {
         	display: grid;
        }
        .g-recaptcha {
         	margin-bottom: 10px;
        }
    </style>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<body>
    <div class="container">
        <div class="row">
        	<div class="col-sm-12">
				<form method="POST">
					<legend>Crashmax Feedback</legend>
					<?php if (!empty($error)) { foreach ($error as $err) { ?>
					<div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><?=$err;?></div>
					<?php } } elseif (isset($success)) { ?>
					<div class="alert alert-success" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Сообщение отправленно, <a href="tg://resolve?domain=<? $channel; ?>&post=<? $TelegramResponse['result']['message_id']; ?>">перейти в канал.</a></div>
					<?php } ?>
					<div class="form-group">
						<label>Введите ваше имя</label>
						<input type="text" class="form-control" name="name" maxlength="80" placeholder="Например, Иван" required>
					</div>
					<div class="form-group">
						<label>Введите E-mail</label>
						<input type="email" class="form-control" name="email" maxlength="80" placeholder="email@gmail.com" required>
					</div>
					<div class="form-group">
						<label>Введите сообщение</label>
						<textarea type="text" class="form-control" name="text" maxlength="300" placeholder="Сообщение" required></textarea>
					</div>
					<div class="g-recaptcha" data-sitekey=""></div><!-- widget token -->
					<div class="grid">
						<button type="submit" class="btn btn-success"><i class="glyphicon glyphicon-envelope"></i> Отправить</button>
					</div>
				</form>
        	</div>
    	</div>
	</div>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
</body>
</html>

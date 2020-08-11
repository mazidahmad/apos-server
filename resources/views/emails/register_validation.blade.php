<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Validasi Akun APOS</title>
</head>
<body>
    <h1>Hai {{$user->name_user}}</h1>
    <p>Terima Kasih telah mendaftar akun di APOS, silahkan konfirmasi melalui <a href="{{ env('URL_APPS') . '/auth/token_validation?token=' . $token . '&id=' . $user->id_user}}">link ini</a></p>
</body>
</html>
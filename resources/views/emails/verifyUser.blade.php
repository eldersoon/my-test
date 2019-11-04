<!DOCTYPE html>
<html>
<head>
    <title>Confirm Email</title>
</head>

<body>
<h2>Hey, {{$user['name']}}, confirme your email clicking on button below!</h2>
<br/>
[link here]
<br/>
<a href="{{url('user/verify', $user->verifyUser->token)}}">Confirm Email</a>
</body>

</html>
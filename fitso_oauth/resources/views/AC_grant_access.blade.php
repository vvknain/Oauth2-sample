<!DOCTYPE html>
<html>
<head>
  <title>Grant Access</title>
</head>
<body>
  <h3>{{ $client_id }} is requesting access to your account:</h3>
  <p>Sign in to grant {{ $client_id }} access.</p>
  <form action="/signin" method="post">
    <p>Username:</p>
    <input type="text" name="username">
    <p>Password:</p>
    <input type="Password" name="password">
    <input type="hidden" name="client_id" value="{{$client_id}}">
    <input type="hidden" name="redirect_url" value="{{$redirect_url}}">
    <p>
      <input type="submit" value="Sign in">
    </p>
  </form>
</body>
</html>
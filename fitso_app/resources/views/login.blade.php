
<!DOCTYPE html>
<html>
<head>
  <title>Sign in</title>
</head>
<body>
  <h3>
    <a href="{{ $dest }}?response_type=code&client_id={{ $client_id }}&redirect_url={{ $redirect_url }}">
    Sign in with Authorization Server</a>
  </h3>
  <br>
  <h2>Here We are redirecting user to sign-in page provided by the authrization server</h2>
</body>
</html>
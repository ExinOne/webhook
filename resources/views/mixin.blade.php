<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Mixin</title>
</head>
<body>
<script>
  var conversation_id = getConversationId();
  console.log(conversation_id);
  window.location.href = "/?conversation_id=" + conversation_id;

  function getConversationId() {
    var userAgent = navigator.userAgent || navigator.vendor || window.opera;

    if (/android/i.test(userAgent)) {
      ctx = window.MixinContext.getContext();
      return JSON.parse(ctx).conversation_id;
    }

    // iOS detection from: http://stackoverflow.com/a/9039885/177710
    if (/iPad|iPhone|iPod/.test(userAgent) && !window.MSStream) {
      ctx = prompt('MixinContext.getContext()');
      return JSON.parse(ctx).conversation_id;
    }

    return "";
  }
</script>
</body>
</html>

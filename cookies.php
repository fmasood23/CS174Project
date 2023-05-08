<?php
setcookie("test_cookie", "test", time() + 3600, '/');
?>
<html>
<body onload = "checkCookiesStats();">

<script type="text/javascript">
      function checkCookiesStats() {
         if(!navigator.cookieEnabled) {
          window.alert("Cookies are disabled");
         }
      }
</script>
<noscript>
         Javasceript is not enabled
</noscript> 

</body>
</html>
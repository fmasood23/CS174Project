<?php
setcookie("test_cookie", "test", time() + 3600, '/');
?>
<html>
<body>

<script type="text/javascript">
      function checkCookiesStats() {
        alert("Cookies are enabled");
         if(navigator.cookieEnabled) {
            alert("Cookies are enabled");
         } else {
            alert("Cookies are disabled");
         }
      }
</script>

</body>
</html>
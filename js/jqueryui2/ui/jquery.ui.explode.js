<!DOCTYPE html>
<html>
<head>
  <link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5/jquery.min.js"></script>
  <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
  <style type="text/css">
  div { margin: 0px; width: 100px; height: 80px; background: green; border: 1px solid black; position: relative; }
</style>

  <script>
  $(document).ready(function() {
    
$("div").click(function () {
      $(this).hide("explode", 1000);
});

  });
  </script>
</head>
<body style="font-size:62.5%;">
  <div></div>
</body>
</html>

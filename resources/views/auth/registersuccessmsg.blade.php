<html>
<head>
    <script type="text/javascript">
        function pageRedirect() {
            window.location.replace("{{ URL::to('/auth/login')}}");
        }
        setTimeout("pageRedirect()", 4000);
    </script>
<style>

    .centered {
        position: fixed;
        top: 50%;
        left: 50%;
        margin-top: -50px;
        margin-left: -100px;
    }
</style>
</head>
<body align="middle">
<font size="4"><b class="centered"> Registered Successfully <br>
        Please check your mail and make confirmation.....<br>
<a href="{{ URL::to('/auth/login')}}">Click here</a></b></font>
</body>
</html>

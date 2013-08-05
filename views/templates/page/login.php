<!DOCTYPE html>
<html class="no-js">
<head>
    <meta charset="utf-8">
    <title>OpenReport</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width">

    <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/css/font-awesome.min.css">

    <style type="text/css">

        body
        {
            padding-top: 40px;
            padding-bottom: 40px;
            background-color: #f5f5f5;
        }

        .brand{
            width: 100%;
            text-align: center;
            font-size: 24px;
        }

        .form-signin
        {
            max-width: 300px;
            padding: 19px 29px 29px;
            margin: 0 auto 20px;
            background-color: #fff;
            border: 1px solid #bbb;
            -webkit-border-radius: 5px;
            -moz-border-radius: 5px;
            border-radius: 5px;
            box-shadow: 0 1px 10px #a7a7a7, inset 0 1px 0 #fff;
        }

        .form-signin .form-signin-heading,
        .form-signin .checkbox
        {
            margin-bottom: 10px;
            font-size: 21px;
        }

        .form-signin input[type="text"],
        .form-signin input[type="password"]
        {
            font-size: 16px;
            height: auto;
            margin-bottom: 15px;
            padding: 4px 9px;
        }

    </style>
</head>
<body>

<div class="container">
    <h2 class="brand" style="color: rgb(42, 35, 231);"><i class="icon-signal icon-black" style="font-size: 24px;color: rgb(238, 126, 23);"></i>&nbsp;OpenReport&nbsp;&nbsp;</h2>
    <form class="form-signin" method="post">

        <h2 class="form-signin-heading">Please sign in</h2>

        <div class="input-prepend">
            <span class="add-on"><i class="icon-large icon-envelope"></i></span>
            <input class="span3" type="text" name="email" placeholder="Email">
        </div>
        <div class="input-prepend">
            <span class="add-on"><i class="icon-large icon-key"></i></span>
            <input class="span3" type="password" name="password" placeholder="Password">
        </div>
        <button class="btn btn-primary" type="submit" name="submit">Sign in</button>

   </form>
</div>
<!-- /container -->

</body>
</html>

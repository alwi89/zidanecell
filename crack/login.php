<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>login zidane cell</title>
<link rel="stylesheet" type="text/css" href="login.css" />
</head>
<body>
	<div id="main">
    <form action="login_proses.php" method="post">
    	<table width="100%" cellpadding="3">
          <tr>
          	<td align="center"><b>LOGIN ZIDANE CELL</b></td>
          </tr>
          <tr>
          	<td align="center">
            	<span class="error"><?php echo isset($_COOKIE['msg'])?$_COOKIE['msg']:''; ?></span>
            </td>
          </tr>
          <tr>
          	<td>
			 	<input name="username" type="text" placeholder="username" />
             </td>
          </tr>
          <tr>
          	<td>
            	<input name="password" type="password" placeholder="password" />
             </td>
          </tr>
          <tr>
          	<td>
            	<input type="submit" value="LOGIN" />
            </td>
          </tr>
        </table>
	</form>
    <!--end main-->
    </div>
</body>
</html>

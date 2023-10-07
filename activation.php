<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // retrieve the activation code from the form
  $activation_code = $_POST['activation_code'];


  // connect to the database
  $mysqli = new mysqli('localhost', 'root', 'root', 'eidyatin_youtube');
  $folder = basename(__DIR__);

  if (strpos($activation_code, "0") === 0) {
    // Search by mobile number
    $stmt = $mysqli->prepare('SELECT * FROM activation_codes WHERE mobile = ? AND dir = ?');
    $formattedNumber = '966' . substr($activation_code, 1);
    $activation_code = $formattedNumber;
    $stmt->bind_param('ss', $activation_code, $folder);
  } else {
    // Search by order number
    $stmt = $mysqli->prepare('SELECT * FROM activation_codes WHERE order_number = ? AND dir = ?');
    $stmt->bind_param('ss', $activation_code, $folder);
  }

  $stmt->execute();
  $result = $stmt->get_result();
  $code = $result->fetch_assoc();

  if (!$code) {
    $certificatesDir = "Certificates";
    $stmt->bind_param('ss', $activation_code, $certificatesDir);
    $stmt->execute();
    $result = $stmt->get_result();
    $code = $result->fetch_assoc();
  
    if ($code && $code['dir'] == "Certificates"){
      $_SESSION[$folder] = true;
      header('Location: interface.php');
      exit();
    }
else{
  echo '<script>alert("هذا الكود غير صالح");</script>';
}
    // the code is invalid
    // display an error message
    
  } else {
    // check if the code has already been used

    if (strtotime($code['expire_date']) < time()) {
      // display an error message if the code has expired
      echo '<script>alert("انتهت صلاحية هذا الكود");</script>';
    } else {
      // the code is valid and has not been used
      // set the session variable to indicate that the user is authenticated
      $_SESSION[$folder] = true;

      // Check if dir is not "Certificates" before starting the session
      if ($code['dir'] !== "Certificates") {
        $_SESSION[$code['dir']] = true;
        header('Location: interface.php');
        exit();
      }
      
      // redirect to the secure page
      header('Location: interface.php');
      exit();
    }
  }
  // close the database connection
  $mysqli->close();
}

?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
  <title>التفعيل</title>
  <meta charset="utf-8">
  <meta http-equiv="Cache-Control" content="no-cache" />
  <meta name="description" content="عبدالقادر المالكي">
  <meta http-equiv="Pragma" content="no-cache" />
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="css/uikit-rtl.css" />
  <link rel="stylesheet" href="css/styles.css" />
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f1f1f1;
    }

    .login-container {
      background-color: #fff;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
      padding: 20px;
      margin: 10px auto;
      max-width: 990px;
      max-height: 100%;
      text-align: center;
    }

    input[type="text"],
    input[type="password"] {
      width: 60%;
      padding: 12px 20px;
      margin: 8px 0;
      box-sizing: border-box;
      border: 2px solid #ccc;
      border-radius: 4px;
      outline: none;
    }

    input[type="submit"] {
      background-color: #4CAF50;
      color: white;
      padding: 12px 20px;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }

    input[type="submit"]:hover {
      background-color: #45a049;
    }

    .error {
      color: red;
    }
  </style>
  <link rel="stylesheet" href="//use.fontawesome.com/releases/v5.0.7/css/all.css">
</head>

<body style="padding-top:10%;padding-bottom:20%; ">

  <div class="login-container">
    <center>
      <h1 class="aid-text">الدخول للمنتج</h1>
    </center>


    <form method="post">
      <label class="aid-text" for="activation_code" class="label"> رقم الطلب أو رقم جوالك وادخل للمنتج بسهولة</label>
      <input type="text" id="activation_code" name="activation_code" placeholder="رقم الطلب أو رقم جوالك" required>
      <div class="uk-margin">

        <input type="submit" value="تــأكـيـد">
      </div>

      <br>
      <div class="uk-margin">
        <a href="http://wa.me/966566304003"<?php echo basename(__DIR__); ?>" target="black">
          لشراء أو تجديد المنتج اضغط هنا
        </a>


        <div class="uk-margin">
          <a href="http://wa.me/966566304003" target="black">
            في حال واجهتك مشكلة تواصل معي بالضغط هنا
          </a>
    </form>
  </div>
  <script>
    // check if the browser supports local storage
    if (typeof(Storage) !== "undefined") {
      // retrieve the activation code from local storage, if it exists
      var activation_code = localStorage.getItem("activation_code");

      // if the activation code exists, populate the input field with it
      if (activation_code) {
        document.getElementById("activation_code").value = activation_code;
      }

      // add an event listener to the form submit button
      document.querySelector('input[type="submit"]').addEventListener('click', function() {
        // save the activation code to local storage
        localStorage.setItem("activation_code", document.getElementById("activation_code").value);
      });
    }
  </script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
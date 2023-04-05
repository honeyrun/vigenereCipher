<?php

    if ($_SERVER['REQUEST_METHOD']=="POST") {

        $key = strtoupper($_POST['key']);
        $keyArray = str_split($key);

        $message = $_POST['message'];
        $msgArray = str_split($message);

        $idx=0;

        foreach ($msgArray as $key=>$letter) {

            if(ctype_upper($letter)){
                $value = ord($letter)-65 + ord($keyArray[$idx])-65;
                if ($value > 25) $value -= 26;
                $replace = chr($value + 65);
            } else if (ctype_lower($letter)){
                $value = ord($letter)-97 + ord($keyArray[$idx])-65;
                if ($value > 25) $value -= 26;
                $replace = chr($value + 97);
            } else continue;

            $msgArray[$key] = $replace;
            $idx++;

            if($idx==count($keyArray)) {
                $idx=0;
            }
        }

        $encryptMsg = implode($msgArray);

        $file = fopen("./data/encrypt.txt",'w');
        fwrite($file,$encryptMsg);
        fclose($file);

        $saveKey = implode($keyArray);

        $file = fopen("./data/key.txt",'w');
        fwrite($file,$saveKey);
        fclose($file);
    
        header("Location: ".$_SERVER['PHP_SELF']."?result=1");
        exit();

    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=`device-width`, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <title>VegEnc</title>
</head>
<body>

    <div class="alert alert-success alert-dismissible fade show" role="alert" style="display: <?php
        if(isset($_GET['result'])&&$_GET['result']=="1") echo "block";
        else echo "none";
    ?> ;">
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    <strong>Alert </strong>Encrypted Successfully
    </div>

    <script>
    var alertList = document.querySelectorAll('.alert');
    alertList.forEach(function (alert) {
        new bootstrap.Alert(alert)
    })
    </script>

    <div class="row justify-content-center align-items-center g-2 mt-5">
        <div class="col-5">
            <h5 class="mb-3">Encryption Page</h5>

            <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <div class="mb-3">
                    <label for="message" class="mb-1">Message</label>
                    <textarea class="form-control" id="exampleFormControlTextarea1" name="message" rows="6"></textarea>
                </div>
                <div class="form-floating mb-3">
                <input
                    type="text"
                    class="form-control" name="key" placeholder="choose your key" required>
                <label for="key">Choose your key</label>
                </div>
                <button type="submit" class="btn btn-primary mb-6">Encrypt</button>
            </form>
            </br>
            <a class="btn btn-primary" href="./data/encrypt.txt" role="button" download style="display:<?php if (isset($_GET['result'])&&$_GET['result']=="1") echo "block"; else echo "none"; ?>"> Download</a>

        </div>
    </div>

</body>
</html>
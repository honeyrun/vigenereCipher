<?php

    if ($_SERVER['REQUEST_METHOD']=="POST") {

        $key = strtoupper($_POST['key']);
        $keyArray = str_split($key);

        $file = fopen('./data/key.txt','r');
        $savedKey = fread($file,filesize('./data/key.txt'));
        fclose($file);

        if ($savedKey!=$key ) {
            header("Location: ".$_SERVER['PHP_SELF']."?result=2");
            exit();
        }

        $messageFile = $_FILES['encrypted_msg'];

        $uploadPath = "./data/uploaded_".$messageFile['name'];
        if($messageFile['type']=="text/plain"){
            if(move_uploaded_file($messageFile['tmp_name'],$uploadPath)){
                if(file_exists($uploadPath)) {

                    $file = fopen($uploadPath,'r');
                    $message = fread($file,filesize($uploadPath));
                    fclose($file);
                    $msgArray = str_split($message);
                    $idx=0;

                    foreach ($msgArray as $key=>$letter) {
                        if(ctype_upper($letter)){
                            $value = ord($letter) - ord($keyArray[$idx]);
                            if ($value < 0) $value += 26;
                            $replace = chr($value + 65);
                        } else if (ctype_lower($letter)){
                            $value = ord($letter)-97 - ord($keyArray[$idx])+65;
                            if ($value < 0) $value += 26;
                            $replace = chr($value + 97);
                        } else continue;

                        $msgArray[$key] = $replace;
                        $idx++;
            
                        if($idx==count($keyArray)) {
                            $idx=0;
                        }
                    }
                    $decryptMsg = implode($msgArray);
                }
            }
        } else {
            echo "This is not a right format";
        }
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
    <title>VegDec</title>
</head>
<body>

    <div class="alert alert-danger alert-dismissible fade show" role="alert" style="display: <?php
        if(isset($_GET['result'])&&$_GET['result']=="2") echo "block"; else echo "none"; ?> ;">
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    <strong>Alert </strong>Decryption failed
    </div>

    <script>
    var alertList = document.querySelectorAll('.alert');
    alertList.forEach(function (alert) {
        new bootstrap.Alert(alert)
    })
    </script>

    <div class="row justify-content-center align-items-center g-2 mt-5">
        <div class="col-5">
            <h5 class="mb-3">Decryption Page</h5>
            <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data">
            <div class="form-floating mb-3">
            <div class="mb-3">
              <label for="file" class="form-label">Choose file</label>
              <input type="file" class="form-control" name="encrypted_msg" id="file" aria-describedby="fileHelpId" required>
            </div>
            <div class="form-floating mb-3">
            <input
                type="text"
                class="form-control" name="key" placeholder="please type your key" required>
            <label for="key">Please type your key</label>
            </div>
            <button type="submit" class="btn btn-primary">Decrypt</button>
            </form>
            </br>
            </br>
            <div class="mb-3" style="display:<?php if($_SERVER['REQUEST_METHOD']=="POST") echo "block"; else echo "none"; ?>">
                <label for="message" class="mb-1">Decrypted Message</label>
                <textarea class="form-control" id="exampleFormControlTextarea1" name="message" rows="6" readonly><?php if ($_SERVER['REQUEST_METHOD']=="POST") echo $decryptMsg; ?>
                </textarea>
            </div>
        </div>
    </div>

</body>
</html>
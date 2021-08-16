

<!DOCTYPE html>
<html>
<head>
    <title>Upload your Image</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css"
          integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no"/>
</head>
<body>
<!-- === File Upload ===
Design a file upload element. Is it the loading screen and icon? A progress element? Are folders being uploaded by flying across the screen like Ghostbusters? ;)
-->
<style>
    body {
        background-color: whitesmoke;
        background-image: url("https://www.transparenttextures.com/patterns/lyonnette.png");
        border-bottom: 0px solid black;
text-align: center;

        height: 100vh;
        width: 100vw;

    }

    /* === Wrapper Styles === */
    #FileUpload {
        display: flex;
        justify-content: center;
    }

    .upload p {
        margin-top: 12px;
        line-height: 0;
        font-size: 22px;
        color: #0c3214;
        letter-spacing: 1.5px;
    }

    label {
        display: block;
        width: 60vw;
        max-width: 300px;
        margin: 0 auto;
        background-color: slateblue;
        color:#fff;
        border-radius: 2px;
        font-size: 1em;
        line-height: 2.5em;
        text-align: center;
    }

    label:hover {
        background-color: cornflowerblue;
    }

    label:active {
        background-color: mediumaquamarine;
    }

    input {
        border: 0;
        clip: rect(1px, 1px, 1px, 1px);
        height: 1px;
        margin: -1px;
       // overflow: hidden;
        padding: 0;
        position: absolute;
        width: 1px;
    }


</style>
<div id="FileUpload">


    <div class="container-fluid">
        <div class="d-flex flex-column">
            <div class="p-2 bd-highlight">
                <img class="img-thumbnail" src="../../assets/images/logo_small.png"/>
            </div>
            <?php
            use Kreait\Firebase\Factory;
            $current_pic  = '';
            if(isset($_GET['key'])){
                require __DIR__.'/../../vendor/autoload.php';
                $key = $_GET['key'];
                $factory = (new Factory)->withDatabaseUri('https://hwwk-bigat.firebaseio.com/');
                $database = $factory->createDatabase();
                $reference = $database->getReference('participants/' . $key);
                $value = $reference->getValue();
                if(isset($value['profile_pic'])){
                    $current_pic = $value['profile_pic'];
                }


            }else{
                $current_pic = "";
            }
            ?>
            <div class="p-2 bd-highlight d-flex align-items-center">
                <img src="<?php echo './'.$current_pic; ?>" id="pickedImage" style="    max-width: 400px; margin: 0 auto;"/>
            </div>
            <div class="p-2 bd-highlight">

                <form method="post" enctype="multipart/form-data" action="uploadfile.php"  style="text-align: center">
                    <div >
                        <input type="text" name="key" value="<?php echo isset($_GET['key']) ?   $_GET['key'] : null ?>"/>
                    </div>
                    <label for="apply">
                        <input type="file" name="profile_pic" id="apply" accept="image/*">Pick a New Image
                    </label>

                    <button name="submit" type="submit" class="btn btn-success " style="margin: 15px auto"> Upload Image</button>

                </form>
            </div>



        </div>

    </div>
</div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj"
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx"
        crossorigin="anonymous"></script>

<script>

    $("input").change(function(e) {

        for (var i = 0; i < e.originalEvent.srcElement.files.length; i++) {

            var file = e.originalEvent.srcElement.files[i];

            var img = document.getElementById("pickedImage");
            img.setAttribute('id','pickedImage')
          img.setAttribute('height','200')

            var reader = new FileReader();
            reader.onloadend = function() {
                img.src = reader.result;
            }
            reader.readAsDataURL(file);
            //$("input").after(img);
        }
    });
</script>
</body>
</html>

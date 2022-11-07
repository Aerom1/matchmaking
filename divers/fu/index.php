<?php include("file-upload.php"); ?>

<!-- Tutorial PHP 8 Upload & Store File/Image in MySQL  https://www.positronx.io/php-upload-store-file-image-in-mysql-database/ -->

<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
  <title>PHP 8 Image Upload Example</title>
  <style>
    .teamContainer {
      max-width: 450px;
    }
  </style>
</head>
<body>


  <div class="container mt-5">

    <form action="" method="post" enctype="multipart/form-data" class="mb-3">

      <h3 class="text-center mb-5">Upload File in PHP</h3>

      <div class="user-image mb-3 text-center">
        <div style="width: 160px; height: 160px; overflow: hidden; background: #cccccc; margin: 0 auto">
          <img src="..." class="figure-img img-fluid rounded" id="imgPlaceholder" alt="">
        </div>
      </div>

      <div class="custom-file">
        <!-- <input type="hidden" name="MAX_FILE_SIZE" value="30" /> -->
        <input type="file" name="fileUpload" class="custom-file-input" id="chooseFile">
        <label class="custom-file-label" for="chooseFile">Select file</label>
      </div>

      <button type="submit" name="submit" class="btn btn-primary btn-block mt-4">
        Upload File
      </button>

    </form>

    <!-- Display response messages -->
    <?php if(!empty($resMessage)) {?>
          <div class="alert <?php echo $resMessage['status']?>">
            <?php echo $resMessage['message']?>
          </div>
    <?php }?>

  </div>


  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script>
    function readURL(input) {
      if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
          $('#imgPlaceholder').attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]); // convert to base64 string
      }
    }
    $("#chooseFile").change(function () {
      readURL(this);
    });
  </script>


</body>
</html>
<?php 

    require "../database/functional.php";

    if (isset($_POST['submit'])){
        $RESPONCREATE = createNewBotCommand($_POST['massage'], $_POST['question']);
    }

    if (isset($_POST['update'])){
        $RESPONUPDATE = updateBotCommand( $_POST['massageID'], $_POST['questionID'], $_POST['question'], $_POST['massage'] );
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>

  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CHATBOT</title>
  <script src="../javascript/jquery.min.js"></script>
  <link rel="stylesheet" href="../css/styleForAdmin.css">
</head>
<body>

  <div class="containerAdminPage">
    <div class="headerAdminPage">
        <a href="?page=list"><button class="headerButton">LIST</button></a>
        <a href="?page=create"><button class="headerButton">CREATE</button></a>
    </div>
    <br>

    <?php 
    
    openPage($_GET['page']);

    ?>

  </div>

</body>
<script>

    function deleteClickFunction(massage, question){
        var check = confirm("are you sure?");
        if ( check ){
            $.post(
                "../database/callback.php",

                {
                    deleted : 0,
                    massageID : massage,
                    questionID : question
                }, 
                
                function(data){
                    location.reload();
                }
            )
        }
    }

</script>
</html>
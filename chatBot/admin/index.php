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
  
  <style>
    
    body{
        margin: 0;
        background-color: wheat;
    }

    .container{
        position: fixed;
        background-color: white;
        border-radius: 32px;
        box-shadow: 2px 4px 8px 2px grey;
        width: 1280px;
        height: 640px;
        margin: 0 auto;
        top: 50%;
        left: 50%;
        transform: translate( -50%, -50% );
        text-align: center;
    }

    .header{
        width: 100%;
        background-color: #bea9ff;
        height: 75px;
        border-top-left-radius: 32px;
        border-top-right-radius: 32px;
    }

    .massageBox{
        position: absolute;
        width: 100%;
        height: 461px;
        top: 75px;
        z-index: 1;
        overflow-y: scroll;
    }

    .botMassage{
        margin: 12px 0 12px 12px;
        padding: 4px 4px;
        background-color: #bea9ff;
        border: 1px solid black;
        width: 94%;
    }

    .personMassage{
        margin: 12px 0 12px 12px;
        padding: 4px 4px;
        background-color: white;
        border: 1px solid black;
        width: 94%;
    }

    .footer{
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 64px;

        background-color: #bea9ff;

        border-bottom-right-radius: 32px;
        border-bottom-left-radius: 32px;

    }

    .error{
        color: red;
    }

    .headerButton{
        float: left;
        margin-left: 8px;
        width: 128px; 
        height: 80%;
        transform: translate(10%, 10%);

        border: 0;
        border-radius: 32px;
        
        font-size: 20px;

        background-color: white;
        color: #bea9ff;

        cursor: pointer;
    }

    .headerButton:hover{
        color: white;
        background-color: grey;
    }

    table{
        margin: 0 auto;
        table-layout:fixed;
        border-collapse: collapse;
        width: 95%;
    }

    th, td{
        border: 1px solid black;
        width: 100%;
    }

    input{
        width: 95%;
        font-size: 20px;
        border-radius: 32px;
        padding: 8px 8px;
        margin-bottom: 12px;
    }

    .notification{
        position: fixed;
        right: 1%;
        top: 1%;
        z-index: 5;

        width: 360px;
        height: 64px;

        border: 4px solid red;
        border-radius: 32px;

        background-color: white;

        text-align: center;
        font-size: 24px;
        line-height: 64px;
    }
  </style>

</head>
<body>

  <div class="container">
    <div class="header">
        <a href="?page=list"><button class="headerButton">LIST</button></a>
        <a href="?page=create"><button class="headerButton">CREATE</button></a>
    </div>
    <br>
    <?php 
    if (!isset($_GET['page'])){
        header("location: index.php?page=list");
    }

    switch($_GET['page']){
        case "list":
            include "list.php";
        break;
        case "create":
            include "create.php";
        break;
        case "update":
            include "update.php";
        break;
    }
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
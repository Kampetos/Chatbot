<?php 

require "database/functional.php";

?>

<!DOCTYPE html>
<html lang="en">
<head>

  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CHATBOT</title>
  <script src="javascript/jquery.min.js"></script>
  
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
        width: 400px;
        height: 600px;
        margin: 0 auto;
        top: 50%;
        left: 50%;
        transform: translate( -50%, -50% );
        text-align: center;
    }

    .header{
        position: absolute;
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
        overflow-y: scroll;
        z-index: 1;
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

    button{
        width: 100%;
        height: 80%;
        font-size: 20px;
        border-radius: 32px;
        border: 0;
        padding: 8px 8px;
        cursor: pointer;
        margin-bottom: 12px;
    }

  </style>

</head>
<body>

  <div class="container">
    <div class="header">
        <h1 style="color: white;">CHATBOT</h1>
    </div>

    <div class="massageBox" id="massage">
        <div class="botMassage">
            <h3 style="text-align: left;">KAMPETOS - BOT</h3>
            <p style="text-align: justify;">hello, how can i help you?</p>
            <p style="text-align: left;">11:10</p>
        </div>
        <div class="personMassage">
            <h3 style="text-align: left;">YOU</h3>
            <p style="text-align: justify;" id="youQuestion"><?php showQuestion(); ?></p>
            <p style="text-align: left;">11:10</p>
        </div>

        
    </div>

    <div class="footer"></div>
  </div>

<script>
    function showAnswer(questionID, ask){

        $.post(
            "database/callback.php",
            
            {
                answer: 0,
                ID: questionID
            },

            function(data){
                $("#youQuestion").text(ask);
                $("#youQuestion").attr("id","w");
                $("#massage").append(
                    "<div class='botMassage'>"+
                    "<h3 style='text-align: left;'>KAMPETOS - BOT</h3>"+
                    "<p style='text-align: justify;'>"+data+"</p>"+
                    "<p style='text-align: left;'>11:10</p>"+
                    "</div>"
                );
            }
        );

        $.post(
            "database/callback.php",
            {
                questing: 0
            },
            function(data){
                $("#massage").append(
                    "<div class='personMassage'>"+
                    "<h3 style='text-align: left;'>YOU</h3>"+
                    "<p style='text-align: justify;' id='youQuestion'>"+data+"</p>"+
                    "<p style='text-align: left;'>11:10</p>"+
                    "</div>"
                );
            }
        );
    }

</script>

</body>
</html>
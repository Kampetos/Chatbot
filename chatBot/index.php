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
  <link rel="stylesheet" href="css/styleForUser.css">

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
        </div>
        <div class="personMassage">
            <h3 style="text-align: left;">YOU</h3>
            <p style="text-align: justify;" id="youQuestion"><?php showQuestion(); ?></p>
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
                    "</div>"
                );
            }
        );
    }

</script>

</body>
</html>
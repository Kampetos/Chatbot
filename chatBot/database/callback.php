<?php 

require "functional.php";

if (isset($_POST['answer'])){
    answerQuestion();
  }

 if (isset($_POST['questing'])){
  showQuestion();
 }

 if (isset( $_POST['deleted'] )){
    deleteBotCommand($_POST['massageID'], $_POST['questionID']);
 }

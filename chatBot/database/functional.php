<?php 

  require "databaseConfiguration.php";

  function connectToDatabase(){
    $mysqli = new mysqli( constant('SERVER'), constant('USERNAME'), constant('PASSWORD'), constant('DATABASE') );

    if ($mysqli -> connect_errno != 0) {
        $errorMassage = $mysqli -> connect_error;
        $getErrorDate = date("d-M-Y");
        echo " { $errorMassage } | { $getErrorDate } \r\n ";
        return false;
      }else{
        return $mysqli;
      }
  }

  function updateBotCommand( $MassageID, $questionID, $massage, $question ){

    //CONFIGURATION
    $mysqli = connectToDatabase();

    //CHECK IF NONE OF THE FIELD IS EMPTY AND SPECIAL CHARACTER
    $array = [ $MassageID, $questionID, $massage, $question ];
    $exp = '/[\"\\\'\<\>\(\)\{\}]/';

    foreach( $array as $value ){

      if ( empty( $value ) ){
        echo "<div class='notification'>all field are required</div>";
        return false; 
      }

      if (preg_match($exp, $value)) {
        echo "<div class='notification'>sorry but you can't use special character on your field</div>";
        return false; 
      }
    }

    //TRY TO UPDATE QUESTION
    $SQL = " UPDATE `question` SET `id_massage` = '$MassageID', `question` = '$question' WHERE `id_question` = ? ";
    $stmt = $mysqli->prepare( $SQL );
    $stmt->bind_param("s",$questionID);
    $stmt->execute();
    
    if (!$stmt){
      echo "<div class='notification'>woops.. something wrong</div>";
      return false; 
    }

    $SQL = " UPDATE `massage` SET `answer` = '$massage' WHERE `id_massage` = ? ";
    $stmt = $mysqli->prepare( $SQL );
    $stmt->bind_param("s",$MassageID);
    $stmt->execute();
    
    if (!$stmt){
      echo "<div class='notification'>woops.. something wrong</div>";
      return false; 
    }

    header("location: index.php?page=list");
  }
  
  function showCommandOnField( $massageID ){

    //CONFIGURATION
    $mysqli = connectToDatabase();

    //TAKE QUESTION AND MASSAGE LIST FROM DATABASE
    $SQL = "  SELECT question.question, massage.answer, massage.id_massage, question.id_question
              FROM question 
              INNER JOIN massage 
              ON question.id_massage = massage.id_massage
              WHERE `id_question` = ? AND massage.id_massage = ?";
    $stmt = $mysqli->prepare ($SQL );
    $stmt->bind_param( "ss", $massageID, $massageID );
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result( $massage, $question, $MassageID, $questionID );

    while ($stmt->fetch()){
      echo "<input type='hidden' name='massageID' id='massageID' value='$MassageID'>";
      echo "<input type='hidden' name='questionID' id='questionID' value='$questionID'>";
      echo "<input type='text' name='massage' id='massage' value='$massage'>";
      echo "<input type='text' name='question' id='question' value='$question'>";
    }
  }

  function deleteBotCommand($massageID, $questionID){

    //CONFIGURATION
    $mysqli = connectToDatabase();

    //DELETE MASSAGE BY ID
    $SQL = " DELETE FROM `massage` WHERE id_massage = ? ";
    $stmt = $mysqli->prepare( $SQL );
    $stmt->bind_param( 's', $massageID );
    $stmt->execute();

    if ($stmt->affected_rows != 1){
      echo "<div class='notification'>Oops... Something Error with deleting massage</div>";
      return false; 
    }
    

    //DELETE QUESTION BY ID
    $SQL = " DELETE FROM `question` WHERE id_question = ? ";
    $stmt = $mysqli->prepare( $SQL );
    $stmt->bind_param( 's', $questionID );
    $stmt->execute();

    if ($stmt->affected_rows != 1){
      echo "<div class='notification'>Oops... Something Error with deleting question</div>";
      return false; 
    }


  }

  function createNewBotCommand($massage, $question){

    //CONFIGURATION
    $mysqli = connectToDatabase();

    //CHECK IF FIELD IS NOT EMPTY
    $array = [$massage, $question];
    $exp = '/[\"\\\'\<\>\(\)\{\}]/';

    foreach($array as $value){
      if(empty($value)){
        echo "<div class='notification'>all field are required</div>";
      return false; 
      }

      if (preg_match($exp, $value)) {
        echo "<div class='notification'>sorry but you can't use special character on your field</div>";
      return false; 
      }
    }

    //TRY TO CREATE A MASSAGE
    $SQL = " INSERT INTO `massage` ( `answer` ) VALUE ( ? ) ";
    $stmt = $mysqli->prepare( $SQL );
    $stmt->bind_param( 's', $massage );
    $stmt->execute();

    if ($stmt->affected_rows != 1){
      echo "<div class='notification'>Oops... Something Error</div>";
      return false; 
    }

    //GET NEWEST GENERATED ID
    $getMassageID = $stmt->insert_id;

    //TRY TO CREATE A QUESTION AND INSERT ID MASSAGE
    $SQL = " INSERT INTO `question` (`id_massage`, `question`) VALUE (?, ?) ";
    $stmt = $mysqli->prepare( $SQL );
    $stmt->bind_param( 'ss', $getMassageID, $question );
    $stmt->execute();

    if ($stmt->affected_rows != 1){
      echo "<div class='notification'>Oops... Something Error</div>";
      return false; 
    }
      
    header("location: index.php?page=list");
    

  }

  function ShowBotCommandList(){

    //CONFIGURATION
    $mysqli = connectToDatabase();
    $i = 0;

    //TAKE QUESTION AND MASSAGE LIST FROM DATABASE
    $SQL = "  SELECT question.question, massage.answer, massage.id_massage, question.id_question
              FROM question 
              INNER JOIN massage 
              ON question.id_massage = massage.id_massage";
    $stmt = $mysqli->prepare($SQL);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($massage, $question, $MassageID, $questionID);
    $stmtNumberOfRow = $stmt->num_rows;

    if (!$stmtNumberOfRow){
      echo "<div class='notification'>oops, something when wrong with table</div>";
      return false; 
    }
    
    //PRINT QUESTION AND MASSAGE LIST TO TABLE
    while ($stmt->fetch()){
      $i += 1;
      echo "
        <tr>
          <td>$i</td>
          <td>$massage</td>
          <td>$question</td>
          <td> <button onclick='deleteClickFunction($MassageID, $questionID)' style='width: 100%; cursor: pointer;'>DELETE</button></td>
          <td> <a href='?page=update&massage=$MassageID'><button style='width: 100%'; cursor: pointer;>UPDATE</button></a></td>
        </tr>";
    }
  }

  function showQuestion(){

    //CONFIGURATION
    $mysqli = connectToDatabase();

    //TAKE AND PRINT QUESTION FROM DATABASE
    $SQL = " SELECT `id_massage`, `question` FROM `question` ";
    $stmt = $mysqli->prepare($SQL);
    $stmt->execute();
    $stmt->store_result();
    $stmtNumberOfRow = $stmt->num_rows;
    $stmt->bind_result($massageID, $question);

    if (!$stmtNumberOfRow){
      echo "<div class='notification'>oops, something when wrong</div>";
      return false; 
    }

    while ($stmt->fetch()){
      echo " <button onclick='showAnswer($massageID, ".'"'.$question.'"'.")'>".$question."</button> <br> ";
    }
  }

  function answerQuestion(){

    //CONFIGURATION
    $mysqli = connectToDatabase();

    //TAKE AND PRINT QUESTION FROM DATABASE
    $SQL = " SELECT `answer` FROM `massage` WHERE id_massage = ? ";
    $stmt = $mysqli->prepare( $SQL );
    $stmt->bind_param( 'i', $_POST['ID'] );
    $stmt->execute();
    $stmt->store_result();
    $stmtNumberOfRow = $stmt->num_rows;
    $stmt->bind_result( $massage );

    if ( !$stmtNumberOfRow ){
      echo "<div class='notification'>oops, something when wrong</div>";
      return false; 
    }

    if ( $stmt->fetch() ){
      echo $massage;
    }
  }

  function openPage($page){
    if ( !isset($page) ){
      header( "location: index.php?page=list" );
      return false;
    }

    switch( $page ){
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
  }
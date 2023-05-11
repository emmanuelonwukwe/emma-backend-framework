<?php
  function showMsg($msg,$msgtype){
    switch($msgtype){
      case 1: ?>
        <!--success alert iv-->
        <div class="alert alert-success alert-dismissible">
          <a href="#" onclick="$('.alert').fadeOut(1000)" class="close" data-dismiss="alert" aria-label="close">&times;</a>
          <strong>Success!</strong>
          <script>setTimeout(fade,10000);</script>
          <?php echo $msg ; ?>
        </div>

      <!--warning alert div-->
      <?php
        break;
        case 2: 
      ?>
      <div class="alert alert-danger alert-dismissible">
        <a href="#" onclick="$('.alert').hide(1000)" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <strong>Oops!</strong>
        <script>setTimeout(fade,10000);</script>
        <?php echo $msg ; ?>
      </div>

    <?php break; } //end of switch 
  } 
?>

<?php
  //This function gives alert message
  function phpAlert($msg){
      echo "<script>alert('$msg')</script>";
  }
?>
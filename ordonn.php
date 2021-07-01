<?php  
session_start(); /* Starts the session */

if($_SESSION['Active'] == false){ /* Redirects user to Login.php if not logged in */
  header("location:aceuil.php");
  exit;
}


//get data from data base


include "config.php";
if (isset($_GET['dateordonn'])and isset($_GET['idconsultation'])) {
  $_SESSION['date']=$_GET['dateordonn'];
  $dateordonn=$_GET['dateordonn'];
$idconsultation=$_GET['idconsultation'];

  $vra_test =0;
  $repons= $conn->prepare('select id_ordonnance,id_consultation,id_pation from consultation where id_consultation= :idcons');
  $repons->execute(array('idcons'=>$_GET['idconsultation']));
  while ($donne = $repons->fetch()) {
  $vara= $donne['id_ordonnance'];
  $pation=$donne['id_pation'];
  $_SESSION['idordonnance']=$donne['id_ordonnance'];
$_SESSION['pation']= $pation;

}
if ($vara==null) {

    $insrt = $conn->prepare('insert into ordonnance (date_ordonnance,id_pation) values(:dateordonn,:idpatient)');
    $insrt->execute(array('dateordonn'=>$dateordonn,
                           'idpatient'=>$pation));
    $insrt->closecursor();

    $rep=$conn->prepare('select id_ordonnance from ordonnance where id_pation=:idpatient and date_ordonnance=:dateordonn ');
    $rep->execute(array('idpatient'=>$pation,
                         'dateordonn'=>$dateordonn));
    while ($donne=$rep->fetch()) {
  $idordonnanc=$donne['id_ordonnance'];
    }

  $update= $conn->prepare('update consultation set id_ordonnance=:idordonnance where id_consultation=:idconsultation');
$update->execute(array('idordonnance'=>$idordonnanc,
                       'idconsultation'=>$idconsultation
                       ));
}
}

//  ajouter une medicament dans un ordonnonce
if (isset($_POST['addmedp'])) {
  $varidordonn=$_POST['idordonnance'];
  $varnommed=$_POST['nom_med'];
  $posologi=$_POST['posologi'];
  $varmedpre=0;
   $repidmed=$conn->prepare('select * from medicament where nom_com=:nom_med');
  $repidmed->execute(array('nom_med'=>$varnommed));
  while ($donne = $repidmed->fetch()) {
    if ($donne['nom_com']==$varnommed) {
      $idmed=$donne['id_medicament'];
}
}
$testmed=$conn->query('select * from medicament_priscrip');
while ($donn=$testmed->fetch()) {
  if ($donn['id_ordonnance']==$varidordonn and $donn['id_medicament']==$idmed and $donn['posologi']==$posologi) {
    $varmedpre=1;
  }
  
}
if ($varmedpre==0) {
   $insrt=$conn->prepare('insert into medicament_priscrip(id_medicament,id_ordonnance,posologi)values(:idmed,:idord,:poso)');
    $insrt->execute(array('idmed'=>$idmed,
                          'idord'=>$varidordonn,
                          'poso'=>$posologi));
}
}
?>
 <!DOCTYPE html>
 <html>
 <head>
 <link rel="shortout icon" type="image/x-icon" href="logo.png">
 <link rel="stylesheet" type="text/css" href="nav.css">
<style>
* {
  box-sizing: border-box;
}

.columns {
  float: left;
  width: 100%;
  padding: 8px;
}

.price {
  list-style-type: none;
  border: 1px solid #eee;
  margin: 0;
  padding: 0;
  -webkit-transition: 0.3s;
  transition: 0.3s;
}

.price:hover {
  box-shadow: 0 8px 12px 0 rgba(0,0,0,0.2)
}

.price .header {
  background-color: #00e6e6;
  color: white;
  font-size: 25px;
}

.price li {
  border-bottom: 1px solid #eee;
  padding: 20px;
}

.price .grey {
  background-color: #eee;
  font-size: 20px;
}

.button {
  background-color: #4CAF50;
  border: none;
  color: white;
  padding: 10px 25px;
  text-align: center;
  text-decoration: none;
  font-size: 18px;
}

@media only screen and (max-width: 600px) {
  .columns {
    width: 100%;
  }
}
</style>
  <meta charset="utf-8">
<title>ajouter ordonnance</title>   <!-- Latest compiled and minified CSS -->
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">

<!-- jQuery library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<!-- Latest compiled JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script> 
 </head>
 <body>
 <a id="dec" href="doctor.php" style="width: 105px;
      height: 30px;
      margin-left: 85%;
      text-decoration: none;
      border-radius: 5px;
      background-color: #00e6e6;
      border: none;
      cursor: pointer;
      font-size: 18px;
      color:black;
      background-color: #00e6e6;
      padding: 5px;">aceuil page </a>
 

  <div class="element">
    <?php
$patio= $_SESSION['pation'];
  $dateordon= $_SESSION['date'];
  $reponse= $conn->prepare('select * from ordonnance where id_pation=:idpat and date_ordonnance=:dateordonn');
  $reponse->execute(array('idpat'=>$patio,
                          'dateordonn'=>$dateordon));
    while ($donne = $reponse->fetch()) {
    ?>


    <div class="columns">
    <ul class="price">
      <li  class="header"><tr id="<?php echo $donne['id_ordonnance'];?>">
        <th data-target="idordonnance" style="margin-right: 10px;"></th>
        <th data-target="date" style="float:right; margin-right: 10px;"><p style="color:black;display:inline;"> Date ordonnance : <?php echo "".$donne['date_ordonnance'];?></p></th>
        <th><a style="margin-left:50%;" href="#" data-role="addmed" data-id="<?php echo $donne['id_ordonnance'];?>">+medicament </a></th>
      </tr>
    </li>
      <li>  <table class="table table-striped">
  <thead>
    <tr style="border: none;">
    <th style="margin-right:200px;float: left;"> id : </th>
    <th style="margin-right:80px;float: left;"> medicament : </th>
      <th style="float: left;margin-right:140px;"> posologi : </th>
     
    </tr>
  </thead>
 <tbody>
    <?php   

    $repon= $conn->prepare('select * from medicament_priscrip,medicament,ordonnance where ordonnance.id_ordonnance=medicament_priscrip.id_ordonnance and medicament.id_medicament=medicament_priscrip.id_medicament and ordonnance.id_ordonnance=:idordonnance');
     $repon->execute(array('idordonnance'=>$donne['id_ordonnance']));
    while ($donne = $repon->fetch()) {
    ?>
     <tr >
      <td data-target="idconsultation" style="float: left;margin-right:200px;border-bottom: none; "><?php echo $donne['id_medicament'];?></td>
      <td data-target="nom_medicamen" style="float: left;margin-right: 140px ;"><?php echo $donne['nom_com'];  ?></td>
      <td data-target="nom_medicamen" style="float: left;margin-right: 140px ;"><?php echo $donne['posologi'];  ?></td>
      <td style="float: right;margin-right: 50px;"><a href="#" data-role="more" data-id="" style="float: left;"></a> </td>
    
    

     
    </tr>
    
 <?php }  ?>
 </table>
</li>
     
    </ul>

</div>


 <?php } 
 ?>
  </div>
  <!-- add medicament-->
  <div id="myModeladdmed" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
        <button  style="cursor: default;
        position: absolute;
        padding: 8px 12px; margin: auto;margin-top: 0px; margin-left: 563px;border-radius: 0px 5px 0px 0px;"  type="button" class="close" data-dismiss="modal">&times;</button>
      <form method="POST" action="ordonn.php">
      <div  style="margin:0px;padding:0px;" class="modal-body">
        <p style="margin-top: 40px;margin-left :100px;">Priscrit un medicament :</p><br>
        <label style="margin-left:200px;display:inline;" for="nom">Nom :</label>
    <select  style="margin-left:0px;width:100px;" required name="nom_med">
              <?php   
    $reponse= $conn->query('select * from medicament');
    while ($donne = $reponse->fetch()) {
    ?>
              <option><?php echo $donne['nom_com']; ?> </option>
              
              <?php } ?>
            </select><br><br>
            <label style="margin-left:200px;display:inline;" for="nom">Posologi :</label>
      <input  style="margin-left:0px;width:50px;" style="margin-left:0px;width:auto;" type="number" name="posologi" id="posologi" placeholder=""><br>
      <input type="hidden" name="idordonnance" id="idordonnance"value="<?php echo $donne['id_ordonnance'];?>" >
      </div>

        <input style="margin-left:230px;" id="submit" type="submit" name="addmedp" value="ajouter">
        
      </form>
    </div>

  </div>
</div>
</tbody>
<script >

  $(document).ready(function(){

    $(document).on('click','a[data-role=addmed]',function(){
     //alert($(this).data('id');
    var id=$(this).data('id');
     var idordonnance=$('#'+id).children('th[data-target=idordonnance]').text();
     $('#idordonnance').val(id);
     $('#myModeladdmed').modal('toggle');

    });

   });  
</script>
</html>

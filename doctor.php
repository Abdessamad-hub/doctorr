<?php
session_start(); /* Starts the session */

if($_SESSION['Active'] == false){ /* Redirects user to Login.php if not logged in */
  header("location:aceuil.php");
  exit;
}
//get data from data base 
include "config.php";

// ajouter consultation
if (isset($_POST['addconsultation'])) {
  $motif=$_POST['motif'];
  $type=$_POST['type'];
  $user=$_POST['patient'];
  $consulta=$_POST['consultation'];
  $exam=$_POST['examen'];
  $vartst=1;
  $ord=null;
   $reponse=$conn->query('select * from pation,rendez_vous where rendez_vous.id_pation=pation.id_pation');
  
  while ($donne = $reponse->fetch()) {
  $var1=$donne['nom']." ".$donne['prenom'];
    if ($var1==$user) {
      $idpatient=$donne['id_pation'];
      $idrdv=$donne['id_rendez_vous'];
}
}  

$rep=$conn->query('select * from consultation ');
while ($donne=$rep->fetch()) {
  if ( $idpatient==$donne['id_pation']and $consulta==$donne['consultation']and  $idrdv==$donne['id_rendez_vous']) {
    $vartst=0;
  }

}
if ($vartst==1) {

   $insrt=$conn->prepare('insert into consultation(motif,typee,examen,consultation,id_pation,id_rendez_vous,id_ordonnance)values(:motif,:type,:examen,:consultation,:idpation,:idrdv,:idordonn)');
    $insrt->execute(array('motif'=>$motif,
                          'type'=>$type,
                          'examen'=>$exam,
                          'consultation'=>$consulta,
                          'idpation'=>$idpatient,
                          'idrdv'=>$idrdv,
                           'idordonn'=>$ord));

}
}
//delet consultation
if (isset($_POST['delet_consultation'])) {
  $idconsultation=$_POST['idconsultation'];
  $delet= $conn->prepare('delete from consultation  where  id_consultation  =:idconsultation');
  $delet->execute(array('idconsultation'=>$idconsultation));
  $delet->closecursor();

}


?>

<html>
    <head>
        <title>aceuil</title>
        <link rel="stylesheet" type="text/css" href="docaceuil.css">
        <link rel="shortout icon" type="image/x-icon" href="logo.png">
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="nav.css">
        <!-- jQuery library -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <!-- Latest compiled JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
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
    </head>
<body>
  
  <div class="sidenav">
    <img src="logo.png" alt="logo" id="logo">
    <h1 id="name">DOCTOR</h1>
    <p id="nn">medical clinic management</p>
      <a id="aceuil" href="doctor.php">aceuil</a>
      <a id="agenda" href="agenda.php">rendez vous</a>
      <a id="patient" href="patient.php">patient</a>
      <a id="medicament" href="docmedicament.php">medicament</a>
    </div>

    <a id="dec" href="logout.php" style="width: 105px;
      height: 30px;
      margin-left: 85%;
      border-radius: 5px;
      border: none;
      cursor: pointer;
      font-size: 18px;
      text-decoration: none;
      color:black;
      background-color: #00e6e6;
      padding: 5px;">Déconnexion</a>
      
      <div class="main">

      <button style="width: 150px;" class="addpatmed" id="addpat" type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModeladd">+consultation</button>
      <h3>liste des consultations : </h3>
 <div  class="element">
   <?php   
 $reponse= $conn->query('select distinct nom,prenom,pation.id_pation from consultation,pation where pation.id_pation=consultation.id_pation order by id_pation desc');
   
   while ($donne = $reponse->fetch()) {
   ?>

   <div  class="columns">
   <ul  class="price">
     <li  class="header"><tr><th style="margin-right: 10px;"><p style="color:black;display:inline;"><?php echo $donne['id_pation']."   ";?></p></th><th style="margin-right: 10px;"><p style="color:black;display:inline;"><?php echo $donne['nom']." ";?></p></th><th style="margin-right: 10px;"><p style="color:black;display:inline;"><?php echo $donne['prenom'];?></p></th></tr></li>
     <li>  <table  class="table table-striped">
 <thead>
   <tr style="border: none;">
     <th style="margin-right:50px;float: left;"> id : </th>
     <th style="float: left;margin-right:50px;"> Date : </th>
     <th style="float: left;margin-right: 500px;"> patient :</th>
   </tr>
 </thead>
<tbody>
   <?php   
   $repon= $conn->prepare('select * from consultation,pation,rendez_vous where pation.id_pation=consultation.id_pation and rendez_vous.id_rendez_vous =consultation.id_rendez_vous  and pation.id_pation=:idpation order by id_consultation desc');
   $repon->execute(array('idpation'=>$donne['id_pation'] ));
   while ($donne = $repon->fetch()) {
   ?>
    <tr id="<?php echo $donne['id_consultation'];  ?>">
     <td data-target="idconsultation" style="float: left;margin-right:30px;border-bottom: none; "><?php echo $donne['id_consultation'];?></td>
     <td data-target="nom_medicamen" style="float: left;margin-right: 30px ;"><?php echo $donne['date_rendez_vous'];  ?></td>
     <td data-target="nom" style="float: left;margin-right: 50px:; "><?php echo $donne['nom']." ";echo $donne['prenom'];  ?></td>
     <td style="float: right;margin-right: 50px;"><a href="#" data-role="modifier" data-id="<?php echo $donne['id_consultation'];?>">modifier</a> </td>
     <td style="float: right;margin-right: 50px;"><a href="#" data-role="delet" data-id="<?php echo $donne['id_consultation'];?>" >supprimer</a> </td>
     <td style="float: right;margin-right: 50px;"><a href="ordonn.php?idconsultation=<?php echo $donne['id_consultation'];?>&dateordonn=<?php echo $donne['date_rendez_vous'];  ?>" data-role="update" data-id="" >+ordonnance</a> </td>

    
   </tr>
   
<?php }  ?>
</table></li>
    
   </ul>

</div>


<?php } ?>
 </div>
  <!--ajoutet consltation-->

<div id="myModeladd" class="modal fade" role="dialog">
 <div class="modal-dialog">

   <!-- Modal content-->
   <div class="modal-content">
       <button  style="cursor: default;
        position: absolute;
        padding: 8px 12px; margin: auto;margin-top: 0px; margin-left: 563px;border-radius: 0px 5px 0px 0px;" type="button" class="close" data-dismiss="modal">&times;</button>

     <form method="POST" action="doctor.php">
     <div style="margin:0px;padding:0px;" class="modal-body">
       <p style="margin-top: 40px;margin-left :100px;">Ajouter les informations de consultation :</p>
       <label style="margin-left:180px;display:inline;" for="patient">patient :</label>
       <select style="margin-left:0px;width:auto;" required  name="patient">
             <?php   
   $reponse= $conn->query('select distinct  nom,prenom from pation,rendez_vous where rendez_vous.id_pation=pation.id_pation');
   while ($donne = $reponse->fetch()) {
   ?>
             <option><?php echo $donne['nom'];echo " "; ?><?php echo $donne['prenom']; ?> </option>
             
             <<?php } ?>
           </select><br>
           <label style="margin-left:180px;display:inline;" for="motif">Motif :</label>
     <input style="margin-left:0px;width:auto;" type="text" name="motif" id="motif" placeholder="motif" required><br>
     <label style="margin-left:180px;display:inline;" for="type">Type :</label>
     <input style="margin-left:0px;width:auto;" type="text" name="type" id="type" placeholder="type" required><br>
     <label style="margin-left:180px;display:inline;" for="examen">Examen :</label>
     <input style="margin-left:0px;width:auto;" type="text" name="examen" id="examen" placeholder="examen" required><br>
     <input type="hidden" name="idpatient" id="idpatient" >
     <label  style="margin-left:180px;" for="consultation">consultation  :</label><br>
     <textarea style="margin-left:180px;" name="consultation" id="consultation" cols="50" rows="4" required></textarea>
     </div> 
       <input style="margin-left:230px;" id="submit" type="submit" name="addconsultation" value="ajouter">
       
     </form>
   </div>

 </div>

 <!--delet consultation -->  

</div>
 
 <!-- Modal -->
<div id="myModaldelet" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
        <button  style="cursor: default;
        position: absolute;
        padding: 8px 12px; margin: auto;margin-top: 0px; margin-left: 563px;border-radius: 0px 5px 0px 0px;" type="button" class="close" data-dismiss="modal">&times;</button>
      <form method="POST" action="doctor.php">
      <div style="margin:0px;padding:0px;" class="modal-body">
        <p  style="margin-top: 40px;margin-left :100px;margin-bettom:0px;">Voulez vous vraiment supprimer cette consultation ?</p>
         <label for="nom"></label>   
      <input type="hidden" name="idconsultation" id="idconsultation"value="<?php echo $idconsultation ;?>">
      </div>

        <input style="margin-left:230px;margin-top:0px;" id="submit" type="submit" name="delet_consultation" value="oui">
        
      
      </form>
    </div>

  </div>
</div>

</div>



 

</body>
<script >
  $(document).ready(function(){

    $(document).on('click','a[data-role=delet]',function(){
     //alert($(this).data('id');
    var id=$(this).data('id');
     var idordonnance=$('#'+id).children('td[data-target=idconsultations]').text();
     $('#idconsultation').val(id);
     $('#myModaldelet').modal('toggle');

    });

   });  
 </script>
 
</html>
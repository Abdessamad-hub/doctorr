<?php

session_start(); /* Starts the session */

if($_SESSION['Active'] == false){ /* Redirects user to Login.php if not logged in */
  header("location:aceuil.php");
  exit;
}


//get data from data base 
include "config.php";




//add rendez-vous
  if ( isset($_POST['add_rendez_vous'])) {
    $nom=$_POST['nom'];
    $daterdv=date($_POST['daterdv']);
    $timerdv=date($_POST['timerdv']);
    $etat=$_POST['etatrdv'];
    $vartst=0;   
    $reponse=$conn->query('select * from pation ');
  
  while ($donne = $reponse->fetch()) {
  $var1=$donne['nom']." ".$donne['prenom'];
    if ($var1==$nom) {
      $idpatient=$donne['id_pation'];
   
}
}
$rep=$conn->query('select * from rendez_vous ');


while ($donne=$rep->fetch()) {

  if ($donne['etat_rendez_vous']==$etat and $donne['date_rendez_vous']==$daterdv and $donne['heurrdv']==$timerdv and $donne['id_pation']=$idpatient ) {
    $vartst=1;

  }
}

  if ($vartst==0) {
    # code...

    $insrt=$conn->prepare('insert into rendez_vous(date_rendez_vous,heurrdv,etat_rendez_vous,id_pation  )values(:daterdv,:hrdv,:etat,:idpatient)');
    $insrt->execute(array('daterdv'=>$daterdv,
                          'hrdv'=>$timerdv,
                          'etat'=>$etat,
                          'idpatient'=>$idpatient,));
    //$insert->closecursor();
  }  
}
// update our rendez-vous
 if (isset($_POST['update_rendez_vous'])) {
    $idrdv=$_POST['idrdv'];
  
    $daterdv=date($_POST['daterdv']);
    $timerdv=date($_POST['timerdv']);
    $etat=$_POST['etatrdv'];
    
    $updaterdv= $conn->prepare('update rendez_vous set  date_rendez_vous= :daterdv,heurrdv=:timerdv, etat_rendez_vous=:etatrdv where id_rendez_vous=:idrdv');
$updaterdv->execute(array('daterdv'=>$daterdv,
                         'idrdv'=>$idrdv,
                       'timerdv'=>$timerdv,
                       'etatrdv'=>$etat));   
}
// delet rendez-vous
 if (isset($_POST['delet_rendez_vous'])) {
      $idrdv=$_POST['idrdvv'];
      $delet= $conn->prepare('delete from rendez_vous  where  id_rendez_vous  =:idrdv');
      $delet->execute(array('idrdv'=>$idrdv));
      $delet->closecursor();

    }
?>

<html>
    <head>
        <title>aceuil</title>
        <link rel="stylesheet" type="text/css" href="docagenda.css">
        <link rel="shortout icon" type="image/x-icon" href="logo.png">
       <!-- Latest compiled and minified CSS -->
       <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="nav.css">
        <!-- jQuery library -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <!-- Latest compiled JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>        
    </head>
<body>
  
  <div class="sidenav">
    <img src="logo.png" alt="logo" id="logo">
    <h1 id="name">DOCTOR</h1>
    <p id="nn">medical clinic management</p>
      <a id="agenda" href="secretair.php">aceuil</a>
      <a id="patient" href="secpatient.php">patient</a>
      <a id="medicament" href="secmedicament.php">medicament</a>
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

      <button class="addpatmed" id="addpat" type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModaladdrdv">ajouter +</button>
      <h3>liste des rendez vous : </h3>
      <div style="overflow-x:auto;">
 <table class="table">
  <thead>
    <tr>
      <th> id </th>
      <th> Nom</th>
      <th> Prenom</th>
      <th> Date </th>
      <th> Heure </th>
      <th>Etat</th>
    </tr>
  </thead>
  <tbody>
    <?php   
    $reponse= $conn->query('select * from pation,rendez_vous where pation.id_pation=rendez_vous.id_pation');
    while ($donne = $reponse->fetch()) {
    ?>
     <tr id="<?php echo $donne['id_rendez_vous'];?>">
      <td data-target="idrendezvous"><?php echo $donne['id_rendez_vous'];  ?></td>
      <td data-target="nom"><?php echo $donne['nom'];  ?></td>
      <td data-target="prenom"><?php echo $donne['prenom'];  ?></td>
      <td data-target="daterdv"><?php echo $donne['date_rendez_vous'];?></td>
      <td data-target="timerdv"><?php echo $donne['heurrdv'];  ?></td>
      <td data-target="etat"><?php echo $donne['etat_rendez_vous'];  ?> </td>
      <td><a href="#" data-role="update" data-id="<?php echo $donne['id_rendez_vous'];?>">modifier</a> </td>
      <td><a href="#" data-role="delet_rendez_vous" data-id="<?php echo $donne['id_rendez_vous'];?>">supprimer</a> </td>
     
    </tr>
    
 <?php }  ?>
 

  </tbody>
 </table> 
 </div>

 <!-- add rende vous -->
<div id="myModaladdrdv" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
        <button style="cursor: default;
        position: absolute;
        padding: 8px 12px; margin: auto;margin-top: 0px; margin-left: 563px;border-radius: 0px 5px 0px 0px;" type="button" class="close" data-dismiss="modal">&times;</button>

        <form action="secretair.php" method="POST">
        <div style="margin:0px;padding:0px;" class="modal-body">
        <p  style="margin-top: 40px;margin-left :100px;">Ajouter a rendez vous :</p>
        <label style="margin-left:180px;display:inline;" for="nom">patient :</label> 
        <select style="margin-left:0px;width:auto;" required name="nom">
              <?php   
                    $reponse= $conn->query('select * from pation');
                    while ($donne = $reponse->fetch()) {
              ?>
              <option><?php echo $donne['nom'];echo " "; ?><?php echo $donne['prenom']; ?> </option>
              <<?php } ?>
        </select><br>
        <label style="margin-left:180px;display:inline;" for="daterdv">Date :</label>
        <input style="margin-left:0px;width:auto;" name="daterdv" id="ddn" placeholder="rv date" type="date" required ><br>
        <label style="margin-left:180px;display:inline;" for="timerdv">Heure :</label>
        <input style="margin-left:0px;width:auto;" name="timerdv" id="ddn" placeholder="rv time" type="time" required  value="00:00:01" ><br>
        <label style="margin-left:180px;display:inline;" for="etatrdv">Etat :</label>
        <input style="margin-left:0px;width:auto;" type="text" name="etatrdv" placeholder="etat de rendez-vous" required><br>
        <input style="margin-left:230px;" id="submit" type="submit"name="add_rendez_vous" value="ajouter" >
      </div>
      </form>
    </div>

  </div>
</div>

<!--   update rendez-vous -->
<div id="myModalupdaterdv" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <button style="cursor: default;
        position: absolute;
        padding: 8px 12px; margin: auto;margin-top: 0px; margin-left: 563px;border-radius: 0px 5px 0px 0px;" type="button" class="close" data-dismiss="modal">&times;</button>
      <form action="secretair.php" method="POST">
      <div style="margin:0px;padding:0px;" class="modal-body">
            <p  style="margin-top: 40px;margin-left :100px;">Modifier votre rendez vous :</p>
            <label style="margin-left:180px;display:inline;" for="daterdv">Date :</label>
            <input style="margin-left:0px;width:auto;" name="daterdv" id="drdv" placeholder="rv date" type="date" required value=""><br>
            <label style="margin-left:180px;display:inline;" for="timerdv">Heure :</label>
            <input style="margin-left:0px;width:auto;" name="timerdv" id="trdv" placeholder="rv time" type="time" required  value="" ><br>
            <label style="margin-left:180px;display:inline;" for="etatrdv">Etat :</label>
            <input style="margin-left:0px;width:auto;" type="text" name="etatrdv" id="erdv" placeholder="etat de rendez-vous" required value="" > <br>
            <input type="hidden" name="idrdv" value="" id="idrdv">
       </div>
        <input style="margin-left:230px;" id="submit" type="submit"name="update_rendez_vous" value="modifier" >
      </form>
    </div>

  </div>
</div>
<!-- delet rendez-vous -->
<div id="myModaldeletrendez_vous" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <button  style="cursor: default;
        position: absolute;
        padding: 8px 12px; margin: auto;margin-top: 0px; margin-left: 563px;border-radius: 0px 5px 0px 0px;" type="button" class="close" data-dismiss="modal">&times;</button>
      <form method="POST" action="secretair.php">
      <div style="margin:0px;padding:0px;" class="modal-body">
      <p  style="margin-top: 40px;margin-left :100px;">voulez vous vraiment supprimer cet rendez vous ?</p>
      <input type="hidden" name="idrdvv"id="id"value="">
      </div>
      <input style="margin-left:230px;" id="submit" type="submit" name="delet_rendez_vous" value="oui">
        
      </div>
      </form>
    </div>

  </div>
</div>

      </div>
    
</body>

<script >
    $(document).ready(function(){

    $(document).on('click','a[data-role=update]',function(){
     //alert($(this).data('id');
     var id=$(this).data('id');
    
     var dater=$('#'+id).children('td[data-target=daterdv]').text();
     var timer=$('#'+id).children('td[data-target=timerdv]').text();
     var etatr=$('#'+id).children('td[data-target=etat]').text();
     var idrdv=$('#'+id).children('td[data-target=idrendezvous]').text();
     $('#drdv').val(dater);
     $('#idrdv').val(idrdv);
     $('#trdv').val(timer);
     $('#erdv').val(etatr);
     $('#myModalupdaterdv').modal('toggle');
    });

   });

 </script>
 <script >
     $(document).ready(function(){
    $(document).on('click','a[data-role=delet_rendez_vous]',function(){
     //alert($(this).data('id');
    var id=$(this).data('id');
     var idpation=$('#'+id).children('td[data-target=idrendezvous]').text();
     $('#id').val(id);
     $('#myModaldeletrendez_vous').modal('toggle');

    });

   });
</script>
</html>
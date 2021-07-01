<?php
session_start(); /* Starts the session */

if($_SESSION['Active'] == false){ /* Redirects user to Login.php if not logged in */
  header("location:aceuil.php");
  exit;
}
include "config.php";  
//add pation

  if (isset($_POST['nom'])and isset($_POST['prenom']) and isset($_POST['email']) and isset($_POST['numerotelf']) and isset($_POST['group']) and isset($_POST['daten'])and isset($_POST['submit'])) {
  $nom=$_POST['nom']; 
  $prenom=$_POST['prenom'];
  $email=$_POST['email'];
  $numero=$_POST['numerotelf'];
  $groupage=$_POST['group'];
  $daten=$_POST['daten'];

  $vra_test =0;
  
  $reponse= $conn->query('select * from pation');
  while ($donne = $reponse->fetch()) {

    if ($donne['nom']==$nom and $donne['prenom']==$prenom and $donne['date_naissance']==$daten) {
      $vra_test=1;
    }
  }
  if ($vra_test==0) {
    $insrt = $conn->prepare('insert into pation(nom, prenom, date_naissance ,groupage ,numero_tele ,adresse_email ) values(:nom ,:prenom , :date_naissance , :groupage, :numero_tele, :adresse_email)');
    $insrt->execute(array('nom' =>$nom,
                               'prenom'=>$prenom,
                               'date_naissance'=>$daten,
                               'groupage'=>$groupage,
                               'numero_tele'=>$numero,
                               'adresse_email'=>$email,
                                 ));


    # code...
  }
}

//delet pation
 if (isset($_POST['delet_pation'])) {
      $idpation=$_POST['idpation'];
      $delet= $conn->prepare('delete from pation  where  id_pation  =:idpation');
      $delet->execute(array('idpation'=>$idpation));
      $delet->closecursor();

    }


//update pation

    if (isset($_POST['update_pation'])) {
$idpation=$_POST['id'];
$nom=$_POST['nom'];
$prenom=$_POST['prenom'];
$daten=$_POST['daten'];
$email=$_POST['email'];
$numerotelf=$_POST['numerotelf'];
$groupage=$_POST['group'];

$update= $conn->prepare('update pation set nom = :nom,prenom=:prenom,date_naissance =:daten,groupage=:groupage ,numero_tele=:numerotelf ,adresse_email=:email where id_pation=:idpation');
$update->execute(array('nom'=>$nom,
                       'prenom'=>$prenom,
                       'groupage'=>$groupage,
                       'daten'=>$daten,
                       'numerotelf'=>$numerotelf,
                       'email'=>$email,
                       'idpation'=>$idpation));
//echo "ddddddddddddddddd"."<br>";
      # code...
    }

    
?>
<html>
    <head>
        <title>patient</title>
        <link rel="stylesheet" type="text/css" href="docpatient.css">
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
<button class="addpatmed" id="addpat" type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModaladdpation">ajouter +</button>
  <div class="conainer">
  <h3>liste des patients : </h3>

<div style="overflow-x:auto;" >
 <table class="table">
  <thead>
    <tr>
      <th> id </th>
      <th>nom</th>
      <th>prenom</th>
      <th>date_de_naissance </th>
      <th>groupage</th>
      <th>Tel</th>
      <th>Email</th>
      <th></th>
    </tr>
  </thead>
  <tbody>
    <?php  
    $reponse= $conn->query('select * from pation');
    while ($donne = $reponse->fetch()) {

    ?>

     <tr id="<?php echo $donne['id_pation'];  ?>">
      <td data-target="idpation"><?php echo $donne['id_pation'];  ?></td>
      <td data-target="nom"><?php echo $donne['nom'];  ?></td>
      <td data-target="prenom"><?php echo $donne['prenom'];  ?></td>
      <td data-target="daten"><?php echo $donne['date_naissance'];  ?></td>
      <td data-target="groupage"><?php echo $donne['groupage'];  ?></td>
      <td data-target="numerotelf"><?php echo $donne['numero_tele'];  ?></td>
      <td data-target="email"><?php echo $donne['adresse_email'];  ?></td>
      <td><a href="#" data-role="update" data-id="<?php echo $donne['id_pation'];?>">modifier</a> </td>
      <td><a href="#" data-role="delet_pation" data-id="<?php echo $donne['id_pation'];?>">supprimer</a> </td>
    </tr>


 <?php }  ?> 

  </tbody>
 </table>
    </div> 
 </div>
 <!-- Modal -->
<div id="myModalupdatepation" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
        <button style="cursor: default;
  position: absolute;
  padding: 8px 12px; margin: auto;margin-top: 0px; margin-left: 563px;border-radius: 0px 5px 0px 0px;" type="button" class="close" data-dismiss="modal">&times;</button>


        <form method="POST" action="patient.php">
      <div style="margin:0px;padding:0px;" class="modal-body">
      <p  style="margin-top: 40px;margin-left :100px;">Entrer les informations da patient :</p>        
   
      <label  style="display:inline;" for="nom">nom :</label>
      <input style="margin-left:0px;width:auto;" type="text" name="nom" id="nom" value="<?php echo $nom ;?>" ><br>
      <label  style="display:inline;" for="prenom"> prenom  :</label>
      <input style="margin-left:0px;width:auto;" type="text" name="prenom"id="prenom" value="<?php echo $prenom ;?>" ><br>
      <label   style="display:inline;" for="email">Email  :</label>
      <input style="margin-left:0px;width:auto;" type="email" name="email"id="email"value="<?php echo $email ;?>" ><br>
      <label  style="display:inline;" for="telf"> Tel  :</label>
      <input style="margin-left:0px;width:auto;" type="numero" name="numerotelf" id="telf" value="<?php echo $numerotelf ;?>" ><br>
      <label  style="display:inline;" for="group"> groupage :</label>
      <input style="margin-left:0px;width: 50px;" type="text" name="group" id="group" value="<?php echo $groupage ;?>" ><br>
      <label  style="display:inline;"  for="daten">date_de_naissance :</label>
      <input style="margin-left:0px;width:auto;" type="date" id="daten" name="daten" value="<?php echo $daten ;?>"><br>
      <input type="hidden" name="id" id="idpation" value="<?php echo $idpation ;?>">
      </div>

        <input style="border: none;
  width: 100px;
  height: 30px;
  border-radius: 5px;
  margin: auto;
   margin-top: 20px;
   margin-left: 40%;
   margin-bottom: 10px;
  background-color:#00e6e6;
  cursor: pointer;
  padding: 0px;
  font-size: 18px;" type="submit" name="update_pation" value="modifier">
      
      </form>
    </div>

  </div>
</div>

      </form>
    </div>

  </div>
</div> 
</div>
 
<!-- Modal -->
<div id="myModaldeletpation" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">

<button style="cursor: default;
  position: absolute;
  padding: 8px 12px; margin: auto;margin-top: 0px; margin-left: 563px;border-radius: 0px 5px 0px 0px;" type="button" class="close" data-dismiss="modal">&times;</button>


<form method="POST" action="patient.php">
      <div style="margin:0px;padding:0px;" class="modal-body">
      <p style="margin-top: 40px;margin-left: 120px;"> voulez-vous vraiment supprimer cet patient ?</p>
        
   
      <input type="hidden" name="idpation"id="id"value="<?php echo $idpation ;?>">
      </div>

        <input  style="border: none;
  width: 100px;
  height: 30px;
  border-radius: 5px;
  margin: auto;
   margin-top: 20px;
   margin-left: 40%;
   margin-bottom: 10px;
  background-color:#00e6e6;
  cursor: pointer;
  padding: 0px;
  font-size: 18px;" type="submit" name="delet_pation" value="oui">
        
      </form>
    </div>

  </div>
</div>

</div>
 
 
 <!-- Modal -->
<div id="myModaladdpation" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
    <button style="cursor: default;
  position: absolute;
  padding: 8px 12px; margin: auto;margin-top: 0px; margin-left: 563px;border-radius: 0px 5px 0px 0px;" type="button" class="close" data-dismiss="modal">&times;</button>


    <form method="POST" action="patient.php">
      <div style="margin:0px;padding:0px;" class="modal-body">
      <p  style="margin-top: 40px;margin-left :100px;">Entrer les informations da patient :</p>        
       
      <label  style="display:inline;" for="nom">nom :</label>
      <input style="margin-left:0px;width:auto;" type="text" name="nom" id="nom" placeholder="nom" required><br>
      <label  style="display:inline;" for="prenom"> prenom  :</label>
      <input style="margin-left:0px;width:auto;" type="text" name="prenom"id="prenom" placeholder="prenom" required ><br>
      <label   style="display:inline;" for="email">Email  :</label>
      <input style="margin-left:0px;width:auto;" type="email" name="email"id="email" placeholder="Email" required ><br>
      <label  style="display:inline;" for="telf"> Tel  :</label>
      <input style="margin-left:0px;width:auto;" type="numbre" name="numerotelf" maxlength="10" id="telf" placeholder="Tel" required ><br>
      <label  style="display:inline;" for="group"> groupage :</label>
      <input style="margin-left:0px;width: auto;" type="text" name="group" maxlength="3" id="group" placeholder="groupage" required ><br>
      <label  style="display:inline;"  for="daten">date_de_naissance :</label>
      <input style="margin-left:0px;width:auto;" type="date" id="daten" name="daten"placeholder="date de naissance" required ><br>

      
      </div>

      <input style="border: none;
  width: 100px;
  height: 30px;
  border-radius: 5px;
  margin: auto;
   margin-top: 20px;
   margin-left: 40%;
   margin-bottom: 10px;
  background-color:#00e6e6;
  cursor: pointer;
  padding: 0px;
  font-size: 18px;" type="submit" name="submit" value="ajouter" >
        
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
     var nom=$('#'+id).children('td[data-target=nom]').text();
     var prenom=$('#'+id).children('td[data-target=prenom]').text();
     var daten=$('#'+id).children('td[data-target=daten]').text();
     var email=$('#'+id).children('td[data-target=email]').text();
     var groupage=$('#'+id).children('td[data-target=groupage]').text();
     var numerotelf=$('#'+id).children('td[data-target=numerotelf]').text();
     var idpation=$('#'+id).children('td[data-target=idpation]').text();
     $('#nom').val(nom);
     $('#idpation').val(idpation);
     $('#prenom').val(prenom);
     $('#email').val(email);
     $('#telf').val(numerotelf);
     $('#group').val(groupage);
     $('#daten').val(daten);
     $('#myModalupdatepation').modal('toggle');
    });

   });
           
 
 
    </script>
<script >
     $(document).ready(function(){
    $(document).on('click','a[data-role=delet_pation]',function(){
     //alert($(this).data('id');
    var id=$(this).data('id');
     var idpation=$('#'+id).children('td[data-target=idpation]').text();
     $('#id').val(id);
     $('#myModaldeletpation').modal('toggle');

    });

   });
</script>
 
     </html>
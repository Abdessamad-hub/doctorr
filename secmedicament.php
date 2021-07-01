<?php

session_start(); /* Starts the session */

if($_SESSION['Active'] == false){ /* Redirects user to Login.php if not logged in */
  header("location:aceuil.php");
  exit;
}
//get data from data base 
include "config.php";
//delet medicament 
    if (isset($_POST['delet_medicament'])) {
      $idmedicament=$_POST['idmedicament'];
      $delet= $conn->prepare('delete from medicament  where  id_medicament  =:idmedicament');
      $delet->execute(array('idmedicament'=>$idmedicament));
      $delet->closecursor();

    }


//update medicament 

if ( isset($_POST['update'])) {
    $nom_medicament=$_POST['nom_medicament'];
    $prix=$_POST['prix'];
    $idmedicament=$_POST['idmedicament'];

$update= $conn->prepare('update  medicament set nom_com = :nom,prix=:prix where id_medicament=:idmedicament');
$update->execute(array('nom'=>$nom_medicament,
                        'idmedicament'=>$idmedicament,
                         'prix'=>$prix));

$update->closecursor();
}


//create medicament
  if ( isset($_POST['add'])) {
    $nom_medicament=$_POST['nom_medicament'];
    $prix=$_POST['prix'];
    $vra_test=0;

  $reponse= $conn->query('select * from medicament ');
  while ($donne = $reponse->fetch()) {
    if ($donne['nom_com']==$nom_medicament and $donne['prix']==$prix) {
    $vra_test=1;
    echo "existe déja";
    }
  }
  if ($vra_test==0) {
    $insrt=$conn->prepare('insert into medicament(nom_com,prix)values(:nom,:prix)');
    $insrt->execute(array('nom'=>$nom_medicament,
                              'prix'=>$prix));
    //$insert->closecursor();


    
  }
  $reponse->closecursor();
}

 ?>

<html>
    <head>
        <title>medicament</title>
        <link rel="stylesheet" type="text/css" href="docmedicament.css">
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
      <a id="aceuil" href="secretair.php">aceuil</a>
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
      <button class="addpatmed" id="addpat" type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModeladd">ajouter +</button>
      <div >
 <h3>liste des medicaments : </h3>
 <table class="table">
  <thead>
    <tr>
      <th> id </th>
      <th>nom medicament</th>
      <th>prix</th>
    </tr>
  </thead>
  <tbody>
    <?php   
    $reponse= $conn->query('select * from medicament ');
    while ($donne = $reponse->fetch()) {
    ?>
     <tr id="<?php echo $donne['id_medicament'];  ?>">
      <td data-target="idmedicament"><?php echo $donne['id_medicament'];  ?></td>
      <td data-target="nom_medicament"><?php echo $donne['nom_com'];  ?></td>
      <td data-target="prix"><?php echo $donne['prix'];  ?></td>
      <td><a href="#" data-role="update" data-id="<?php echo $donne['id_medicament'];?>">modifier</a> </td>
      <td><a href="#" data-role="delet" data-id="<?php echo $donne['id_medicament'];?>">supprimer</a> </td>
     
    </tr>
    
 <?php }  ?>
 

  </tbody>
 </table> 
 </div>


 
 <!-- Modal -->
<div id="myModalupdate" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
        <button type="button"  
        style="cursor: default;
        position: absolute;
        padding: 8px 12px; margin: auto;margin-top: 0px; margin-left: 563px;border-radius: 0px 5px 0px 0px;"
   class="close" data-dismiss="modal">&times;</button>
   <div style="margin:0px;padding:0px;" class="modal-body">
   <p  style="margin-top: 40px;margin-left: 100px;">Entrer les informations de medicament :</p>
      <form method="POST" action="secmedicament.php">
      
      <label style="display:inline;" for="nom_medicament">Nom :</label>
      <input required  style="display:inline;margin: auto;
   margin-top: 10px;
   margin-left:20px;
    border: none;
    width: 250px;
    height: 35px;
    background-color:rgba(255, 255, 255,0.0);
    border-bottom: 1px solid;
    padding: 10px;" type="text" name="nom_medicament" id="nom_medicament" value="<?php echo $nom_medicament ;?>" ><br><br>
      <label style="display:inline;" for="prix"> Prix  :</label>
      <input required  style="display:inline;margin: auto;
   margin-top: 10px;
   margin-left:20px;
    border: none;
    width: 250px;
    height: 35px;
    background-color:rgba(255, 255, 255,0.0);
    border-bottom: 1px solid;
    padding: 10px;"  type="number" name="prix" id="prix" value="<?php echo $prix ;?>" ><br><br>   
      
      <input type="hidden" name="idmedicament" id="idmedicament"value="<?php echo $idmedicament ;?>">
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
  font-size: 18px;" type="submit" name="update" value="modifier">
      </form>
    </div>

  </div>
</div>
</div>
 
 <!-- Modal -->
<div id="myModaldelet" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div  class="modal-content">
        <button style="cursor: default;
  position: absolute;
  padding: 8px 12px; margin: auto;margin-top: 0px; margin-left: 563px;border-radius: 0px 5px 0px 0px;" type="button" class="close" data-dismiss="modal">&times;</button>
     <div style="margin:0px;padding:0px;" class="modal-body">
     <form method="POST" action="secmedicament.php">
          <p style="margin-top: 40px;margin-left: 120px;"> voulez-vous vraiment supprimer cet medicament ?</p>
      <input  type="hidden" name="idmedicament" id="idmed"value="<?php echo $idmedicament ;?>">
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
  font-size: 18px;" type="submit" name="delet_medicament" value="oui">
      </form>
    </div>

  </div>
</div>
<div id="myModeladd" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      
        <button type="button"
        style="cursor: default;
        position: absolute;
        padding: 8px 12px; margin: auto;margin-top: 0px; margin-left: 562px;border-radius: 0px 5px 0px 0px;"
        class="close" data-dismiss="modal">&times;</button>
        <p  style="margin-top: 40px;margin-left:100px;">Entrer les informations de medicament :</p>
      <form method="POST" action="secmedicament.php">
      <label style="display:inline;" for="nom">Nom :</label>
      <input required style="display:inline;margin: auto;
   margin-top: 10px;
   margin-left:20px;
    border: none;
    width: 250px;
    height: 35px;
    background-color:rgba(255, 255, 255,0.0);
    border-bottom: 1px solid;
    padding: 10px;" type="text" name="nom_medicament" id="nom" placeholder="nom"><br>
      <label style="display:inline;" for="prix."> Prix  :</label>
      <input required style="display:inline;margin: auto;
   margin-top: 10px;
   margin-left:20px;
    border: none;
    width: 250px;
    height: 35px;
    background-color:rgba(255, 255, 255,0.0);
    border-bottom: 1px solid;
    padding: 10px;" type="number" name="prix" id="prix." placeholder="prix"><br>
     
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
  font-size: 18px;" type="submit" name="add" value="ajouter">     
      </form>

        </div>
        
      </div>
    
</body>

<script >

   
   $(document).ready(function(){

    $(document).on('click','a[data-role=update]',function(){
     //alert($(this).data('id');
    var id=$(this).data('id');
     var nom_medicament=$('#'+id).children('td[data-target=nom_medicament]').text();
     var idmedicament=$('#'+id).children('td[data-target=idmedicament]').text();
     var prix=$('#'+id).children('td[data-target=prix]').text();
     $('#nom_medicament').val(nom_medicament);
     $('#idmedicament').val(idmedicament);
     $('#prix').val(prix);
     $('#myModalupdate').modal('toggle');
    });

   });
     
    
    </script>
 <script >
    $(document).ready(function(){

    $(document).on('click','a[data-role=delet]',function(){
     //alert($(this).data('id');
    var id=$(this).data('id');
     var idmedicament=$('#'+id).children('td[data-target=idmedicament]').text();
     $('#idmed').val(idmedicament);
     $('#myModaldelet').modal('toggle');

    });

   });
 </script>
</html>
<?php include("config.php"); ?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>HKHK spordipäev 2025</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
</head>
<body>
  <div class="container">
    <h1>HKHK spordipäev 2025!</h1>

    <?php


      if(isset($_GET["muuda"]) && isset($_GET["id"])){
        $id = $_GET["id"];
        $kuvaparing = "SELECT * FROM sport2025 WHERE id=".$id."";
        $saadetud_paring = mysqli_query($yhendus, $kuvaparing);
        $rida = mysqli_fetch_assoc($saadetud_paring);
        var_dump($rida);
      }

    ?>


    <form action="index.php" method="get">
      Nimi: <input type="text" name="fullname" required value="<?php echo $rida['fullname']; ?>"><br>
      E-mail: <input type="email" name="email" required><br>
      Vanus: <input type="number" name="age" min="16" max="88" step="1" required><br>
      Sugu: <input type="text" name="gender" required><br>
      Spordiala: <input type="text" name="category" required><br>
      <input type="submit" value="Salvesta" name="salvesta" class="btn btn-primary"><br>
    </form>
    
    <?php

      if(isset($_GET['msg'])){
        echo "<div class='alert alert-success'>".$_GET['msg']."</div>";
      }


      if(isset($_GET['kustuta']) && isset($_GET['id'])){
        $id = $_GET['id'];
        $kparing = "DELETE FROM sport2025 WHERE id=".$id."";
        $saada_paring = mysqli_query($yhendus, $kparing);
        $tulemus = mysqli_affected_rows($yhendus);
        if($tulemus == 1){
          header('Location: index.php?msg=Rida kustutatud');
        } else {
          echo "Kirjet ei lisatud";
        }
      }


      if(isset($_GET["salvesta"]) && !empty($_GET["fullname"])){

        $fullname = $_GET["fullname"];
        $email = $_GET["email"];
        $age = $_GET["age"];
        $gender = $_GET["gender"];
        $category = $_GET["category"];

        $lisa_paring = "INSERT INTO sport2025 (fullname,email,age,gender,category) 
        VALUES ('".$fullname."','".$email."', '".$age."', ' ".$gender."', '".$category."')";

        $saada_paring = mysqli_query($yhendus, $lisa_paring);
        $tulemus = mysqli_affected_rows($yhendus);
        if($tulemus == 1){
          echo "Kirje edukalt lisatud";
        } else {
          echo "Kirjet ei lisatud";
        }
      }


    ?>

    <form action="index.php" method="get" class="py-4">
      <input type="text" name="otsi">
      <select name="cat">
        <option value="fullname">Nimi</option>
        <option value="category">Spordiala</option>
      </select>
      <input type="submit" value="Otsi...">
    </form>
      <?php
              if(isset($_GET['msg'])){
                echo "<div class='alert alert-success'>".$_GET['msg']."</div>";
              }
      ?>
    <table class="table table-striped">
      <thead>
        <tr>
          <th scope="col">id</th>
          <th scope="col">firstname</th>
          <th scope="col">email</th>
          <th scope="col">age</th>
          <th scope="col">gender</th>
          <th scope="col">category</th>
          <th scope="col">reg_time</th>
          <th scope="col">muuda</th>
          <th scope="col">kustuta</th>
        </tr>
      </thead>
      <tbody>

        <?php
            if(isset($_GET['otsi']) && !empty($_GET["otsi"])){
              $s = $_GET['otsi'];
              $cat = $_GET['cat'];
              echo "Otsing: ".$s;
              $paring = 'SELECT * FROM sport2025 WHERE '.$cat.' LIKE "%'.$s.'%"';
 
            } else {
              $paring = "SELECT * from sport2025 LIMIT 50";
            }
            
            $saada_paring = mysqli_query($yhendus, $paring);

            while($rida = mysqli_fetch_assoc($saada_paring)){


                echo "<tr>";
                echo "<td>".$rida['id']."</td>";
                echo "<td>".$rida['fullname']."</td>";
                echo "<td>".$rida['email']."</td>";
                echo "<td>".$rida['age']."</td>";
                echo "<td>".$rida['gender']."</td>";
                echo "<td>".$rida['category']."</td>";
                echo "<td>".$rida['reg_time']."</td>";
                echo "<td><a class='btn btn-success' href='?muuda&id=".$rida['id']."'>Muuda</a></td>";
                echo "<td><a class='btn btn-danger' href='?kustuta&id=".$rida['id']."'>Kustuta</a></td>";
                echo "</tr>";

            }
        ?>

      </tbody>
    </table>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>
</body>
</html>  
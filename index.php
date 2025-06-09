<?php include("config.php"); ?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>sport</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
</head>
<body>
  <div class="container">
    <h1>Spordipäev HKHKs 2025</h1>
    <a href="login.php" class="btn btn-warning">Admin Leht</a>
    <form action="index.php" method="get" class="py-4">
      <input type="text" name="otsi">
      <select name="cat">
        <option value="fullname">Nimi</option>
        <option value="category">Spordiala</option>
      </select>
      <input type="submit" value="Otsi...">
    </form>

    <table class="table table-striped">
      <thead>
        <tr>
        <th scope="col">id</th>
          <th scope="col">nimi</th>
          <th scope="col">email</th>
          <th scope="col">vanus</th>
          <th scope="col">sugu</th>
          <th scope="col">kategooria</th>
          <th scope="col">aeg</th>
          <th scope="col">muuda adminlehel</th>
        </tr>
      </thead>
      <tbody>

        <?php

            $uudiseid_lehel = 50;

            $uudiseid_kokku_paring = "SELECT COUNT('id') FROM sport2025";
            $lehtede_vastus = mysqli_query($yhendus, $uudiseid_kokku_paring);
            $uudiseid_kokku = mysqli_fetch_array($lehtede_vastus);
            $lehti_kokku = $uudiseid_kokku[0];
            $lehti_kokku = ceil($lehti_kokku/$uudiseid_lehel);
            echo 'Lehekülgi kokku: '.$lehti_kokku.'<br>';
            echo 'Uudiseid lehel: '.$uudiseid_lehel.'<br>';
            if (isset($_GET['page'])) {
              $leht = $_GET['page'];
            } else {
              $leht = 1;
            }
            $start = ($leht-1)*$uudiseid_lehel;


            if(isset($_GET['otsi']) && !empty($_GET["otsi"])){
              $s = $_GET['otsi'];
              $cat = $_GET['cat'];
              echo "<tr><td span='6'>Otsing: ".$s."</td></tr>";
              $paring = 'SELECT * FROM sport2025 WHERE '.$cat.' LIKE "%'.$s.'%"';
            } else {

              $paring = "SELECT * from sport2025 LIMIT $start, $uudiseid_lehel";
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
                echo "</tr>";

            }
            $eelmine = $leht - 1;
            $jargmine = $leht + 1;
            if ($leht>1) {
              echo "<a  class='btn btn-secondary m-1' href='?page=$eelmine'>Eelmine</a> ";
            }
            if ($lehti_kokku >= 1) {
              for ($i=1; $i<=$lehti_kokku ; $i++) { 
                if ($i==$leht) {
                  echo "<b><a  class='btn btn-secondary m-1' href='?page=$i'>$i</a></b> ";
                } else {
                  echo "<a  class='btn btn-secondary m-1' href='?page=$i'>$i</a> ";
                }
                
              }
            }
            if ($leht<$lehti_kokku) {
            echo "<a  class='btn btn-secondary m-1' href='?page=$jargmine'>Järgmine</a> ";
            }
        ?>

      </tbody>
    </table>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>
</body>
</html>
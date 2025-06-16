<?php 
include("config.php"); 
puhasta_aegunud_broneeringud($yhendus);
?>

<!DOCTYPE html>
<html lang="et">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kino Piletite Broneerimine</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .hero-section {
            background: rgba(0,0,0,0.7);
            color: white;
            padding: 4rem 0;
            margin-bottom: 3rem;
        }
        
        .movie-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            overflow: hidden;
            margin-bottom: 2rem;
        }
        
        .movie-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
        }
        
        .movie-poster {
            width: 100%;
            height: 300px;
            object-fit: cover;
        }
        
        .movie-info {
            padding: 1.5rem;
        }
        
        .genre-badge {
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.8rem;
            display: inline-block;
            margin-bottom: 0.5rem;
        }
        
        .duration {
            color: #666;
            font-size: 0.9rem;
        }
        
        .btn-primary {
            background: linear-gradient(45deg, #667eea, #764ba2);
            border: none;
            border-radius: 25px;
            padding: 0.7rem 2rem;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        
        .admin-btn {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
            background: rgba(255,255,255,0.9);
            color: #333;
            border: none;
            border-radius: 50px;
            padding: 0.8rem 1.5rem;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .admin-btn:hover {
            background: white;
            color: #333;
            transform: scale(1.05);
        }
        
        .search-section {
            background: rgba(255,255,255,0.95);
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 3rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <a href="admin/login.php" class="admin-btn">
        <i class="fas fa-user-shield"></i> Admin
    </a>

    <div class="hero-section text-center">
        <div class="container">
            <h1 class="display-4 mb-3">
                <i class="fas fa-film"></i> Kino Piletite Broneerimine
            </h1>
            <p class="lead">Moell INC</p>
        </div>
    </div>

    <div class="container">
        <div class="search-section">
            <h3 class="mb-3"><i class="fas fa-search"></i> Otsi filme</h3>
            <form method="GET" class="row g-3">
                <div class="col-md-6">
                    <input type="text" class="form-control" name="otsi" placeholder="Filmi nimi..." 
                           value="<?php echo isset($_GET['otsi']) ? htmlspecialchars($_GET['otsi']) : ''; ?>">
                </div>
                <div class="col-md-4">
                    <select name="zanr" class="form-select">
                        <option value="">Kõik žanrid</option>
                        <?php
                        $zanrid_paring = "SELECT DISTINCT zanr FROM filmid WHERE aktiivne = 1 ORDER BY zanr";
                        $zanrid_vastus = mysqli_query($yhendus, $zanrid_paring);
                        while($zanr = mysqli_fetch_assoc($zanrid_vastus)) {
                            $selected = (isset($_GET['zanr']) && $_GET['zanr'] == $zanr['zanr']) ? 'selected' : '';
                            echo "<option value='" . htmlspecialchars($zanr['zanr']) . "' $selected>" . 
                                 htmlspecialchars($zanr['zanr']) . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search"></i> Otsi
                    </button>
                </div>
            </form>
        </div>

        <div class="row">
            <?php
            // Filmide päring koos otsingu ja filtreerimisega
            $where_tingimused = ["f.aktiivne = 1"];
            
            if (isset($_GET['otsi']) && !empty($_GET['otsi'])) {
                $otsi = mysqli_real_escape_string($yhendus, $_GET['otsi']);
                $where_tingimused[] = "f.nimi LIKE '%$otsi%'";
            }
            
            if (isset($_GET['zanr']) && !empty($_GET['zanr'])) {
                $zanr = mysqli_real_escape_string($yhendus, $_GET['zanr']);
                $where_tingimused[] = "f.zanr = '$zanr'";
            }
            
            $where_klausel = implode(' AND ', $where_tingimused);
            
            $filmide_paring = "
                SELECT f.*, 
                       COUNT(DISTINCT s.id) as seansside_arv,
                       MIN(s.hind) as min_hind
                FROM filmid f 
                LEFT JOIN seansid s ON f.id = s.film_id AND s.aktiivne = 1 AND s.kuupaev >= CURDATE()
                WHERE $where_klausel
                GROUP BY f.id 
                ORDER BY f.nimi
            ";
            
            $filmide_vastus = mysqli_query($yhendus, $filmide_paring);
            
            if (mysqli_num_rows($filmide_vastus) == 0) {
                echo '<div class="col-12 text-center">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> Otsingu kriteeriumitele vastavaid filme ei leitud.
                        </div>
                      </div>';
            }
            
            while($film = mysqli_fetch_assoc($filmide_vastus)) {
                $tunnid = floor($film['kestus'] / 60);
                $minutid = $film['kestus'] % 60;
                $kestus_tekst = $tunnid . "h " . $minutid . "min";
                
                echo '<div class="col-lg-4 col-md-6">
                        <div class="movie-card">
                            <img src="' . ($film['poster_url'] ?: 'https://images.pexels.com/photos/7991579/pexels-photo-7991579.jpeg') . '" 
                                 alt="' . htmlspecialchars($film['nimi']) . '" class="movie-poster">
                            <div class="movie-info">
                                <span class="genre-badge">' . htmlspecialchars($film['zanr']) . '</span>
                                <h5 class="card-title mb-2">' . htmlspecialchars($film['nimi']) . '</h5>
                                <p class="duration mb-2">
                                    <i class="fas fa-clock"></i> ' . $kestus_tekst . ' | ' . 
                                    htmlspecialchars($film['vanusepiirang']) . '
                                </p>
                                <p class="card-text text-muted mb-3">' . 
                                   htmlspecialchars(substr($film['kirjeldus'], 0, 100)) . '...
                                </p>';
                
                if ($film['seansside_arv'] > 0) {
                    echo '<div class="d-flex justify-content-between align-items-center">
                            <span class="text-success">
                                <i class="fas fa-ticket-alt"></i> ' . $film['seansside_arv'] . ' seanss(i)
                            </span>
                            <span class="fw-bold">Alates €' . number_format($film['min_hind'], 2) . '</span>
                          </div>
                          <a href="seansid.php?film_id=' . $film['id'] . '" class="btn btn-primary w-100 mt-3">
                              <i class="fas fa-calendar-alt"></i> Vali seanss
                          </a>';
                } else {
                    echo '<div class="text-center mb-3">
                            <span class="text-muted">
                                <i class="fas fa-calendar-times"></i> Seansse pole saadaval
                            </span>
                          </div>
                          <a href="broneeri.php?film_id=' . $film['id'] . '" class="btn btn-primary w-100">
                              <i class="fas fa-ticket-alt"></i> Broneeri
                          </a>';
                }
                
                echo '    </div>
                        </div>
                      </div>';
            }
            ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

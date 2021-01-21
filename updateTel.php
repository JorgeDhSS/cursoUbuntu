<?php
// Initialize the session
session_start();
 
// If session variable is not set it will redirect to login page
if(!isset($_SESSION['name']) || empty($_SESSION['name'])){
    //if($_SESSION['usertype'] != 2)
           header("location: login.php");
  exit;
}

// Include config file
require_once 'db.php';
 
// Define variables and initialize with empty values
$nombre = $_SESSION['name'];
$nombre_err = "";
 
// Processing form data when form is submitted
if(isset($_POST["Dato"]) && !empty($_POST["Dato"])){
    // Get hidden input value
    $dato = $_POST["Dato"];
    
   
    // Check input errors before inserting in database
    if(empty($nombre_err)){
        // Prepare an insert statement
        $sql = "UPDATE usuarios SET tel=? WHERE Nombre=?";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ss", $param_id, $param_nombre);
            
            // Set parameters
            $param_nombre = $nombre;
            $param_id = $dato;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records updated successfully. Redirect to landing page
                header("location: /curso/login.php");
                exit();
            } else{
                echo "Something went wrong. Please try again later.";
            }
        }
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // Close connection
    mysqli_close($link);
} else{
    // Check existence of id parameter before processing further
    if(isset($_GET["dato"]) && !empty(trim($_GET["dato"]))){
        // Get URL parameter
        $dato =  trim($_GET["dato"]);
        echo $dato;
    }  else{
        // URL doesn't contain id parameter. Redirect to error page
        header("location: error.php");
        exit();
    }
}
?>
 
<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Agregar integrantes </title>
    <meta name= "description" content="Contenido educativo">
    <meta name="keywords" content="Servicios Ubuntu">
    <meta name="author" content="Desarrolladores">


    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
      
  </head>
  <header>
<!-- menu --> 
      <div class="container-fluid bg-dark fixed-top text-center">
         <nav class="navbar navbar-expand-lg navbar-light bg-light navbar-dark bg-dark container">
              <a class="navbar-brand" href="#"><img src="imagenes/Ubuntu.png" width="30" height="30" alt=""> Curso de servicios Ubuntu
             </a>
             
              <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
              </button>

              <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <div class="navbar-nav ml-auto">
                  <a class="nav-item nav-link active" href="#">Inicio <span class="sr-only">(current)</span></a>
                  <a class="nav-item nav-link" href="#">Sesiones</a>
                  <a class="nav-item nav-link" href="#">Opiniones</a>
                
                <input class="form-control mr-sm-2" type="search" placeholder="Buscar" aria-label="Search">
                <button class="btn btn-outline-info my-2 mr-4 my-sm-0" type="submit">Buscar</button>
                    <div> <a href="login.html" class="btn btn-success"> Entrar </a></div>
                </div>
              </div>
            </nav> 
          </div>
    </header>
      <!-- fin menu -->
      <!-- slider -->
      <br>
      <br>
      <br>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header">
                        <h2>Actualizar datos</h2>
                    </div>
                    <p>Por favor modifica los datos que gustes y después da click en el botón "Cambiar".</p>
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                        <div class="form-group <?php echo (!empty($nombre_err)) ? 'has-error' : ''; ?>">
                            <label>Tu dato a cambiar</label>
                            <input type="text" name="Dato" class="form-control" value="<?php echo $dato; ?>">
                            <span class="help-block"><?php echo $nombre_err;?></span>
                        </div>
                       
                        <input type="hidden" name="dato" value="<?php echo $dato; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Cambiar">
                        <a href="index.php" class="btn btn-default">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
    <script src="js/bootstrap.min.js"></script>
</body>
    
    <footer class="container-fluid bg-dark text-white py-5 md-4">
          <center><h4><img src="imagenes/Ubuntu.png" width="15" height="15" class="d-inline-block align-top" alt="logo ubuntu"> Curso de servicios ubuntu.</h4>
            <p> Contacto:</p>
        </center>
      </footer>
    
</html>
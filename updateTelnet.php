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
$nombre = "";
$nombre_err = "";
 
// Processing form data when form is submitted
if(isset($_POST["id"]) && !empty($_POST["id"])){
    // Get hidden input value
    $id = $_POST["id"];
    
    // Validate sabortamal
    $input_nombre = trim($_POST["nombre"]);
    if(empty($input_nombre)){
        $nombre_err = "Please enter a sabor tamal.";
    } elseif(!filter_var(trim($_POST["nombre"]), FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z'-.\s ]+$/")))){
        $nombre_err = 'Please enter a valid sabor tamal.';
    } else{
        $nombre = $input_nombre;
    }
    
    
    
    // Check input errors before inserting in database
    if(empty($nombre_err)){
        // Prepare an insert statement
        $sql = "UPDATE telnet SET Nombre=? WHERE id=?";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "si", $param_nombre, $param_id);
            
            // Set parameters
            $param_nombre = $nombre;
            $param_id = $id;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records updated successfully. Redirect to landing page
                header("location: agregarIntegrantes.php");
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
    if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
        // Get URL parameter
        $id =  trim($_GET["id"]);
        
        // Prepare a select statement
        $sql = "SELECT Nombre FROM telnet WHERE id = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $param_id);
            
            // Set parameters
            $param_id = $id;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);
    
                if(mysqli_num_rows($result) == 1){
                    /* Fetch result row as an associative array. Since the result set
                    contains only one row, we don't need to use while loop */
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    
                    // Retrieve individual field value
                    $nombre = $row["Nombre"];
                } else{
                    // URL doesn't contain valid id. Redirect to error page
                    header("location: error.php");
                    exit();
                }
                
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
        
        // Close statement
        mysqli_stmt_close($stmt);
        
        // Close connection
        mysqli_close($link);
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
                    <p>Por favor modifica los datos que gustes y despu[es da click en el bot[on "Cambiar".</p>
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                        <div class="form-group <?php echo (!empty($nombre_err)) ? 'has-error' : ''; ?>">
                            <label>Nombre</label>
                            <input type="text" name="nombre" class="form-control" value="<?php echo $nombre; ?>">
                            <span class="help-block"><?php echo $nombre_err;?></span>
                        </div>
                       
                        <input type="hidden" name="id" value="<?php echo $id; ?>"/>
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
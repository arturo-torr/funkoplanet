<script>
    // JavaScript para limpiar el localStorage al cerrar sesión
    let user = localStorage.getItem('user');
    console.log(user)
    if (user) {
        //localStorage.removeItem(`carrito_${user}`);
        localStorage.removeItem('user');
    }
</script>
<?php
session_start();
session_unset();
session_destroy();
?>

<?php
header("Location: /funkoplanet/index.php");
exit();
?>
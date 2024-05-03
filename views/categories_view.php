<?php
echo "<div id='product-list' class='row mx-auto w-75 text-center'>
<h1 class='purple mt-2'>Categorías</h1>
<hr class='purple_line'>";


foreach ($daoCategorias->categorias as $key => $value) {
  echo "<div class='col-sm-12 col-md-6 col-lg-4 col-xl-4 my-3'>";
  $conte = $value["foto"];
  echo "<a href='#'><div class='category rounded'><img src='data:image/jpg;base64,$conte' class='img-fluid rounded'>
         </div></a></div>";
}
echo "</div>";

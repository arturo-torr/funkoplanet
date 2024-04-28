<?php
echo "<div id='product-list' class='row mx-auto text-center'>
<h1 class='purple mt-2'>Categorías</h1>
<hr class='purple_line'>";

foreach ($daoCategorias->categorias as $key => $value) {
  echo "<div class='col-sm-4 col-lg-4 col-md-4 col-xl-4 my-3'>
    <a class='no_decoration' data-category='" . $value["id"] . "' href='#product-list'>
      <div class='rounded p-3 bg_purple category_scale'>
        <h3 class='text-white'>" . $value["nombre"] . "</h3>
        <span class='text-white'>" . $value["descripcion"] . "</span>
      </div>
    </a>
  </div>";
}
echo "</div>";

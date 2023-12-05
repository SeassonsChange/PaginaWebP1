<div class="articulo">
    <input type="hidden" id="id" value="<?php echo $item['idCarro']; ?>">
    <div class="imagen"><img src="../assets/imagenes/<?php echo $item['Url']; ?>"></div>
    <div class="titulo"><?php echo $item['Nombre']?></div>
    <div class="precio">$<?php echo $item['Precio']?> MXN</div>
    <div class="botones">
        <button class='btn-add'>Agregar al carrito</button>

    </div>
</div>

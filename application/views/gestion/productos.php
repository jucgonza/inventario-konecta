<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Inventario de Productos</title>

	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
	<link href="<?= base_url() ?>/assets/css/global.css" rel="stylesheet">
	<link href="<?= base_url() ?>/assets/css/gestion.css" rel="stylesheet">

</head>
<body>
	<div id="main_content" class="full_vpheight">
		<div class="container py-4">
			<div class="row">
				<div class="col">
					<h1 class="text-light">Gestion de productos</h1>
					<table class="table table-dark table-striped">
						<thead>
							<tr>
								<th scope="col">ID</th>
								<th scope="col">Nombre</th>
								<th scope="col">Stock</th>
								<th scope="col">Creación</th>
								<th scope="col"></th>
							</tr>
						</thead>
						<tbody>
							<?php if(!empty($productos)): ?>
								<?php foreach($productos as $prod): ?>
									<tr>
										<th scope="row"><?= $prod['id_producto'] ?></th>
										<td><?= $prod['nombre'] ?></td>
										<td><?= $prod['stock'] ?></td>
										<td><?= $prod['fecha_creacion'] ?></td>
										<td>
											<a href="<?= base_url() ?>index.php/Gestion/producto/ver/<?= $prod['id_producto'] ?>">Ver</a>
											<a href="<?= base_url() ?>index.php/Gestion/producto/editar/<?= $prod['id_producto'] ?>">Editar</a>
											<a href="<?= base_url() ?>index.php/Gestion/producto/eliminar/<?= $prod['id_producto'] ?>">Eliminar</a>
										</td>
									</tr>
								<?php endforeach; ?>
							<?php else: ?>
								<tr>
									<th scope="row" colspan="5" class="text-center">No hay registros disponibles, por favor crea nuevos productos.</th>
								</tr>
							<?php endif; ?>
						</tbody>
					</table>
				</div>
				<div class="col">
					<div class="bg-light py-2 px-3" id="contenedor_formulario_producto">
						<h3 class="text-black-50">Información del producto</h3>
						<p><?= isset($resultado) ? json_encode($resultado) : '' ?></p>
						<hr>
						<form method="POST" action="<?= base_url() ?>index.php/Gestion/guardar_producto">
							<div class="row">
								<div class="col-6 my-2">
									<label for="nombre" class="form-label">Nombre</label>
									<input type="text" name="nombre" class="form-control" id="nombre" placeholder="Ingrese el nombre del producto" value="<?= set_value('nombre',$producto_info['nombre'] ?? '') ?>" required>
									<?= form_error('nombre','<span class="text-danger">',"</span>"); ?>
								</div>
								<div class="col-6 my-2">
									<label for="referencia" class="form-label">Referencia</label>
									<input type="text" name="referencia" class="form-control" id="referencia" placeholder="Ingrese la referencia" required value="<?= set_value('referencia',$producto_info['referencia'] ?? '') ?>">
									<?= form_error('referencia','<span class="text-danger">',"</span>"); ?>
								</div>
								<div class="col-6 my-2">
									<label for="precio" class="form-label">Precio</label>
									<input type="number" name="precio" class="form-control" id="precio" placeholder="$ 0" required value="<?= set_value('precio',$producto_info['precio'] ?? '') ?>">
									<?= form_error('precio','<span class="text-danger">',"</span>"); ?>
								</div>
								<div class="col-6 my-2">
									<label for="peso" class="form-label">Peso</label>
									<input type="number" name="peso" class="form-control" id="peso" placeholder="Kilogramos" required value="<?= set_value('peso',$producto_info['peso'] ?? '') ?>">
									<?= form_error('peso','<span class="text-danger">',"</span>"); ?>
								</div>
								<div class="col-6 my-2">
									<label for="categoria" class="form-label">Categoria</label>
									<select class="form-select" name="categoria" id="categoria" aria-label="Seleccionar la categoria de producto" required >
										<option selected disabled value="">Seleccione una opción</option>
										<?php if(!empty($categorias)): ?>
											<?php foreach($categorias as $cat): ?>
												<option <?= set_select('categoria',$producto_info['categoria_id'] ?? '') ?> value="<?= $cat['id_categoria'] ?>"><?= $cat['nombre'] ?></option>
											<?php endforeach; ?>
										<?php else: ?>
											<option selected disabled value="">No hay categorias disponibles</option>
										<?php endif; ?>
									</select>
									<?= form_error('categoria','<span class="text-danger">',"</span>"); ?>
								</div>
								<div class="col-6 my-2">
									<label for="stock" class="form-label">Stock</label>
									<input type="number" name="stock" class="form-control" id="stock" placeholder="Cantidad items" required value="<?= set_value('stock',$producto_info['stock'] ?? '1') ?>">
									<?= form_error('stock','<span class="text-danger">',"</span>"); ?>
								</div>
							</div>
							<div class="row py-3">
								<div class="col-12 text-center">
									<button type="submit" class="btn btn-outline-success mx-2">Guadar</button>
									<button type="reset" class="btn btn-outline-secondary mx-2">Cancelar</button>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
</html>
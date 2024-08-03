<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Votación</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
	<?php

		require_once './crsf.php';
		generateCsrfToken();
		if (isset($_SESSION['message'])) {
			$messageType = $_SESSION['message']['type'];
			$messageText = htmlspecialchars($_SESSION['message']['text']);
			echo "<div class='alert alert-{$messageType} ' id='msm-save'>{$messageText}</div>";
			unset($_SESSION['message']);
		}

	?>

	<div class="container m-3 ">
		<h3 class="display-5 fw-bold mb-5">FORMULARIO DE VOTACIÓN</h3>
		<form id="voto_form" action="/votacion-test/index.php?action=create" method="POST">
		<input type="hidden" name="csrf_token" value="<?php echo getCsrfToken(); ?>">

			<div class="row  mb-4">
				<div class="col-4">
					<label for="nombre">Nombre y Apellido:</label>
				</div>
				<div class="col-8">
					<input class="form-control" type="text" name="nombre" id="nombre" required>
				</div>
			</div>
			<div class="row  mb-4">
				<div class="col-4">
					<label for="alias">Alias:</label>
				</div>
				<div class="col-8">
					<input class="form-control" type="text" name="alias" id="alias" placeholder="Mínimo 5 caracteres, incluyendo números y letras." required>
				</div>
			</div>
			<div class="row  mb-4">
				<div class="col-4">
					<label for="rut">RUT:</label>
				</div>
				<div class="col-8">
					<input class="form-control" type="text" name="rut" id="rut" required placeholder="ejemplo 10236547-5">
					<span id="rut-error" class="text-danger"></span>
				</div>
			</div>
			<div class="row  mb-4">
				<div class="col-4">
					<label for="email">Email:</label>
				</div>
				<div class="col-8">
					<input class="form-control" type="email" name="email" id="email" required>
				</div>
			</div>
			<div class="row  mb-4">
				<div class="col-4">
					<label for="region">Region:</label>
				</div>
				<div class="col-8">
					<select id="region" name="region" required class="form-select">
						<option value="">Seleccione una región</option>
						<?php foreach ($regiones as $region) : ?>
							<option value="<?= htmlspecialchars($region['id']) ?>"><?= htmlspecialchars($region['nombre']) ?></option>
						<?php endforeach; ?>
					</select>
				</div>
			</div>
			<div class="row  mb-4">
				<div class="col-4">
					<label for="comuna">Comuna:</label>
				</div>
				<div class="col-8">
					<select id="comuna" name="comuna" required class="form-select">
						<option value="">Seleccione una comuna</option>
					</select>
				</div>
			</div>
			<div class="row  mb-4">
				<div class="col-4">
					<label for="candidato">Candidato:</label>
				</div>
				<div class="col-8">
					<select id="candidato" name="candidato" class="form-select" required>
						<option value="">Seleccione un candidato</option>
						<?php foreach ($candidatos as $candidato) : ?>
							<option value="<?= htmlspecialchars($candidato['id']) ?>"><?= htmlspecialchars($candidato['nombre']) ?></option>
						<?php endforeach; ?>
					</select>
				</div>
			</div>
			<div class="row  mb-4">
				<div class="col-4">
					<label for="medio">Como se enteró de Nosotros</label>
				</div>
				<div class="col-8">
					<?php foreach ($medios as $medio) : ?>
						<div class="form-check form-check-inline">
							<input class="form-check-input" type="checkbox" name="medio[]" value="<?= htmlspecialchars($medio['id']) ?>">
							<label class="form-check-label" for="inlineCheckbox1"><?= htmlspecialchars($medio['medio']) ?></label>
						</div>
					<?php endforeach; ?>
					<br><span class="text-danger" id="check-error"></span>
				</div>
			</div>
			<div class="mt-5">
				<button type="submit" class="btn btn-primary ">Votar</button>
			</div>

		</form>
	</div>
	<script src="/votacion-test/js/validacion.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>
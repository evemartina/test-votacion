document.addEventListener('DOMContentLoaded', () => {
    const form       = document.getElementById('voto_form');
    const rut        = document.getElementById('rut');
    const rutError   = document.getElementById('rut-error');
    const region     = document.getElementById('region');
    const comuna     = document.getElementById('comuna');
    const candidato  = document.getElementById('candidato');
    const nombre     = document.getElementById('nombre');
    const alias      = document.getElementById('alias');
    const email      = document.getElementById('email');
    const checkboxes = document.querySelectorAll('input[name = "medio[]"]');
    const checkError = document.getElementById('check-error');

    async function validateRutBase(rut) {
        const response = await fetch('/votacion-test/index.php?action=checkRut&rut=' + encodeURIComponent(rut));
        const result = await response.json();
        return result.exists;
    }

    /**
	 * La función `validateRut` en JavaScript se utiliza para validar un número RUT 
	 * chileno comprobando su formato y verificando su dígito de control.
	 * La función calcula el VD esperado basándose en la parte del cuerpo del número RUT.
	 * La función `validateRut` devuelve un valor booleano. Devuelve `true` si el
	 *  RUT es válido, y `false` si no es válido.
	 */
 
	function validateRut(rut) {
		const value = rut.replace(/\./g, '');//quitar los punto
		if (value.length < 8) return false;
		const rutSplit = value.split('-');//creo un array separando con el - parte 0: cuerpo parte 1 el DV
		const body = rutSplit[0];
		const dv = rutSplit[1];
		let sum = 0;
		let multiplier = 2;
		for (let i = body.length-1; i >= 0; i--) {
			sum += body[i] * multiplier;
			multiplier = (multiplier === 7) ? 2 : multiplier + 1;
		}
		const expectedDv = (11 - (sum % 11)) % 11;
		const dvMap = { 10: 'K', 11: '0' };
		return dvMap[expectedDv] === dv || expectedDv === parseInt(dv);
	}


    function validateRutField() {
        const isValid = validateRut(rut.value);
        rut.classList.toggle('is-valid', isValid);
        rut.classList.toggle('is-invalid', !isValid);
        rutError.textContent = '';
        if (isValid) {
            // Verifica si el RUT ya existe en la base de datos
            validateRutBase(rut.value).then(exists => {
                if (exists) {
                    rut.classList.remove('is-valid');
                    rut.classList.add('is-invalid');
                    rutError.textContent = 'Este rut ya emitio su voto.';
                }
            });
        }
    }

    function validateField(field, condition, validClass, invalidClass) {
        if (condition) {
            field.classList.remove(invalidClass);
            field.classList.add(validClass);
        } else {
            field.classList.remove(validClass);
            field.classList.add(invalidClass);
        }
    }

    function validateNombre() {
        validateField(nombre, nombre.value.trim() !== '', 'is-valid', 'is-invalid');
    }

    function validateAlias() {
        const isValid = alias.value.length >= 5 &&
                        /[a-zA-Z]/.test(alias.value) &&
                        /\d/.test(alias.value);
        validateField(alias, isValid, 'is-valid', 'is-invalid');
    }

    function validateEmail() {
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        validateField(email, emailPattern.test(email.value), 'is-valid', 'is-invalid');
    }

    function validateCheckboxes() {
        const selectedCount    = document.querySelectorAll('input[name="medio[]"]:checked').length;
        const isValid          = selectedCount >= 2;
        checkError.textContent = isValid ? '' : 'Por favor, selecciona al menos dos opciones.';
        return isValid;
    }

    function validateSelect(select, defaultValue) {
        validateField(select, select.value !== defaultValue, 'is-valid', 'is-invalid');
    }

    region.addEventListener('change', function() {
        this.classList.remove('is-invalid');
        const regionId = this.value;
        const url = '/votacion-test/index.php?action=getComunas&region_id=';

        if (regionId) {
            fetch(url + regionId)
                .then(response => response.text())
                .then(data => comuna.innerHTML = data)
                .catch(error => {
                    console.error('Error:', error);
                    comuna.innerHTML = '<option value="">Ocurrió un error al cargar las comunas</option>';
                });
        } else {
            comuna.innerHTML = '<option value="">Seleccione una comuna</option>';
        }
    });

    comuna.addEventListener('change', function() {
        this.classList.remove('is-invalid');
    });

    candidato.addEventListener('change', function() {
        this.classList.remove('is-invalid');
    });

	rut.addEventListener('input', function () {
		let rut = this.value.toUpperCase().replace(/[^0-9K]/g, ''); // Aceptar solo números y la letra K
		rut = rut.replace(/^0+/, '');
		if (rut.length > 1) {
			rut = rut.slice(0, -1).replace(/\B(?=(\d{3})+(?!\d))/g, '.') + '-' + rut.slice(-1);
		}
		this.value = rut;
	});



    rut.addEventListener('blur', validateRutField);
    nombre.addEventListener('blur', validateNombre);
    alias.addEventListener('blur', validateAlias);
    email.addEventListener('blur', validateEmail);

    form.addEventListener('submit', function(event) {
        event.preventDefault();
        validateNombre();
        validateRutField();
        validateAlias();
        validateEmail();
        const checkboxesValid = validateCheckboxes();
        validateSelect(region, '');
        validateSelect(comuna, '');
        validateSelect(candidato, '');

        if (document.querySelectorAll('.is-invalid').length === 0 && checkboxesValid) {
            form.submit();
        }
    });

    var successMessage = document.getElementById('msm-save');
    if (successMessage) {
        setTimeout(function() {
            successMessage.style.display = 'none';
        }, 3000);
    }
});

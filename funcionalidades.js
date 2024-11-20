function validarFormulario() {
    var username = document.getElementById("username").value;
    var password = document.getElementById("password").value;

    if (username === "" || password === "") {
        alert("Por favor, ingresa el usuario y la contraseña.");
        return false;
    }
}

function showRegistrationOptions() {
    var initialContent = document.getElementById("initialContent");
    var registrationOptions = document.getElementById("registrationOptions");

    initialContent.style.display = "none"; // Oculta content1
    registrationOptions.style.display = "block"; // Muestra content2
}

function showInitialContent() {
    var initialContent = document.getElementById("initialContent");
    var registrationOptions = document.getElementById("registrationOptions");
    var form = document.querySelector('form[action="RegistroAlumno.php"]');

    // Muestra el contenido inicial y oculta el formulario de registro
    initialContent.style.display = "block";
    registrationOptions.style.display = "none";
    
    // Limpia los campos del formulario
    if (form) {
        form.reset();
    }
}


function validarTelefono(event) {
    event.target.value = event.target.value.replace(/\D/g, '').slice(0, 10);
}

let operationSuccess = false; // Variable global para el estado de la operación
document.querySelector('form[action="RegistroAlumno.php"]').addEventListener('submit', function(event) {
    event.preventDefault(); // Evita el envío del formulario de la manera tradicional

    const formData = new FormData(this);

    fetch('RegistroAlumno.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        const popup = document.getElementById('popup');
        const popupText = document.getElementById('popup-text');
        const copyButton = document.getElementById('copy-btn');

        // Oculta el botón de copiar por defecto
        copyButton.style.display = 'none';

        if (data.status === 'success') {
            operationSuccess = true; // Marca la operación como exitosa
            // Mostrar el mensaje y el botón de copiar
            popupText.innerText = data.message;
            copyButton.style.display = 'block'; // Muestra el botón de copiar

            // Manejar la funcionalidad de copiado
            copyButton.addEventListener('click', function() {
                navigator.clipboard.writeText(data.copyText)
                    .then(() => {
                        alert('Credenciales copiadas al portapapeles');
                    })
                    .catch(err => {
                        console.error('Error al copiar:', err);
                    });
            });

        } else if (data.status === 'exists') {
            // Mostrar el mensaje de que la cuenta ya existe sin el botón de copiar
            popupText.innerText = data.message;

        } else if (data.status === 'error') {
            // Mostrar el mensaje de error
            popupText.innerText = data.message;
        }

        // Mostrar el popup
        popup.style.display = 'block';
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Hubo un problema al registrar la cuenta.');
    });
});

function copyToClipboard() {
    const text = document.getElementById("popup-text").innerText;
    navigator.clipboard.writeText(data.copyText).then(function() {
        alert("Texto copiado al portapapeles");
    }, function(err) {
        alert("Error al copiar el texto: ", err);
    });
}

function closePopup() {
    document.getElementById("popup").style.display = "none";
    if (operationSuccess) {
        window.location.href = 'index.html'; // Redirige al inicio
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const togglePassword = document.querySelector('#togglePassword');
    const password = document.querySelector('#inputPassword');

    // Función para mostrar/ocultar la contraseña
    togglePassword.addEventListener('click', function (e) {
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);
        this.querySelector('img').src = type === 'password' ? 'eye-icon.png' : 'eye-slash-icon.png';
    });

    // Función para mostrar el ícono solo si hay texto
    password.addEventListener('input', function() {
        if (this.value) {
            togglePassword.style.display = 'block'; // Muestra el ícono
        } else {
            togglePassword.style.display = 'none'; // Oculta el ícono
        }
    });

    // Inicialmente oculta el ícono
    togglePassword.style.display = 'none';
});
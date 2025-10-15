// === FUNCIONES PARA EL MODAL ===
function openUpdateModal(button) {
  const modal = document.getElementById("updateModal");
  modal.style.display = "flex";

  // Rellenar los campos con los datos del botón seleccionado
  document.getElementById("userid").value = button.getAttribute("data-id");
  document.getElementById("username").value = button.getAttribute("data-username");
  document.getElementById("userpassword").value = "";
  document.getElementById("userprofile").value = "";
  document.getElementById("role").value = button.getAttribute("data-role");
}

function closeModal(id) {
  document.getElementById(id).style.display = "none";
}

// === ACTUALIZAR USUARIO ===
document.getElementById("updateuser").addEventListener("click", async () => {
  const id = document.getElementById("userid").value;
  const username = document.getElementById("username").value;
  const password = document.getElementById("userpassword").value;
  const role = document.getElementById("role").value;
  const image = document.getElementById("userprofile").files[0];

  if (!id || !username) {
    alert("❌ Faltan datos requeridos.");
    return;
  }

  // Crear FormData para enviar datos + imagen
  const formData = new FormData();
  formData.append("id", id);
  formData.append("username", username);
  formData.append("password", password);
  formData.append("role", role);
  if (image) formData.append("image", image);

  try {
    const response = await fetch("UpdateUser.php", {
      method: "POST",
      body: formData
    });

    const result = await response.text();
    alert(result);

    if (result.includes("✅")) {
      // Cerrar modal
      closeModal("updateModal");

      // Actualizar datos en la interfaz
      const empleado = document.getElementById(`empleado_${id}`);
      if (empleado) {
        empleado.querySelector(".nombre").textContent = username;
        empleado.querySelector(".rol").textContent = "Rol: " + document.getElementById("role").selectedOptions[0].text;
        if (image) {
          const reader = new FileReader();
          reader.onload = function (e) {
            empleado.querySelector("img").src = e.target.result;
          };
          reader.readAsDataURL(image);
        }
      }
    }

  } catch (error) {
    alert("❌ Error al enviar los datos: " + error.message);
  }
});



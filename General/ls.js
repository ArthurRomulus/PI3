fetch("session.php", {
    method: "POST",
    body: new FormData(document.getElementById("formLogin"))
})
.then(res => res.json())
.then(data => {
    console.log(data);

    if (data.status === "ok") {

        // Guardar sesión en localStorage:
        localStorage.setItem("session", JSON.stringify(data.session));

        // Redirección desde JS
        if (data.role == 4) {
            window.location.href = "../Admin/Admin_Inicio/index.php";
        } else if (data.role == 2) {
            window.location.href = "../Cajero/Inicio/Inicio.html";
        } else {
            window.location.href = "../coffeeShop/Inicio";
        }
    } else {
        alert(data.message);
    }
})
.catch(err => console.error(err));

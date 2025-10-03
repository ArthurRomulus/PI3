function closeModal(element){
    document.getElementById(element).style.display = "None";
}

function openModal(element){
    document.getElementById(element).style.display = "flex";
}

window.openModal = openModal;
window.closeModal = closeModal;
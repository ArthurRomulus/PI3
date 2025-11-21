<?php
session_start();
?>

<link rel="stylesheet" href="../AdminProfileSesion.css">

<?php

$ur = $_SERVER["REQUEST_URI"];

if (stripos($ur, "admin_perfil") !== false) {
    echo "<div class='top-bar' style='display: none;'>";
} else {
    echo "<div class='top-bar'>";
}

?>
<div class="admin-profile">

    <img src='<?php echo htmlspecialchars($_SESSION['profilescreen']); ?>'>

    <span class="admin-name">
        <?php 
        if (!empty($_SESSION['username'])) {
            echo htmlspecialchars($_SESSION['username']); 
        }
        ?>
    </span>

    <script src="../../theme-toggle.js" defer></script>

    <div class="theme-switch-wrapper">
        <label class="theme-switch" for="theme-toggle">
            <input type="checkbox" id="theme-toggle" />
            <div class="slider round"></div>
        </label>
    </div>

    <div class="lang-switch">
        <img src="../../Images/es_flag.png" id="btn-es" class="lang-flag active" alt="Español" title="Español">
        <img src="../../Images/uk_flag.png" id="btn-en" class="lang-flag" alt="English" title="English">
    </div>

    <style>
        .lang-switch {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .lang-flag {
            width: 28px;
            height: 18px;
            cursor: pointer;
            opacity: 0.6;
            transition: transform 0.2s ease, opacity 0.3s ease;
            border-radius: 3px;
        }

        .lang-flag:hover {
            opacity: 1;
            transform: scale(1.1);
        }

        .lang-flag.active {
            opacity: 1;
            box-shadow: 0 0 6px rgba(133, 73, 5, 0.8);
        }

        .top-bar .lang-switch .lang-flag {
            width: 38px !important;
            height: 28px !important;
            border-radius: 3px !important;
            object-fit: cover;
        }
    </style>

    <form method="POST" action="../../General/logout.php">
        <button type="submit" class="logoutButton" data-translate="Cerrar sesión">Cerrar sesión</button>
    </form>

</div> 
</div>

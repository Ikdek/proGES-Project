
<?php
session_start();
?>

<nav class="nav">
    <div class='logo'>ProGES</div>
    <ul class='menu'>
        <li><a href='index.php'>accueil</a></li>
        <li><a href='plannings.php'>plannings</a></li>
        <li><a href='marks.php'>Notes</a></li>
        <?php if (isset($_SESSION['connected']) && $_SESSION['rank'] == 'admin'): ?>
            <li><a href='admin.php'>Administration</a></li>
        <?php endif; ?>
        <li><a href='deconnexion.php'>déconnexion</a></li>
    </ul>
    <button class='hamburger'>
        <span class='icon'></span>
    </button>
</nav>

<link rel="stylesheet" href="STYLE/navbarAdmin.css">

<?php
if (empty($_SESSION) || $_SESSION['rank'] != 'admin') {
    header("Location: index.php");
}
?>
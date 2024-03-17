<?php
    session_start();
    function verifierConnexion($pseudo, $password)
    {
        global $myUsers;
        foreach ($myUsers as $user) {
            if ($user['pseudo'] === $pseudo && $user['password'] === $password) {
                $_SESSION['connected'] = true;
                $_SESSION['pseudo'] = $pseudo;
                return true;
            }
        }
        return false;
    }

    if (empty ($_SESSION)) {
        header("Location: login.php");
    }


    ?>
-- Comptes de démonstration pour le développement local.
--
--   admin / admin  -> rank « admin »        (accès à admin.php)
--   user  / user   -> rank « utilisateur »
--
-- Format imposé par login.php et createUser.php :
--   `login`    = hash('sha256', pseudo)
--   `password` = password_hash(mot_de_passe, PASSWORD_DEFAULT)  [bcrypt]
--
-- Rejouable : les comptes sont supprimés puis recréés.

DELETE FROM users WHERE login IN (
  '8c6976e5b5410415bde908bd4dee15dfb167a9c873fc4bb8a81f6f2ab448a918', -- sha256('admin')
  '04f8996da763b7a969b1028ee3007569eaf3a635486ddab211d512c85b9df8fb'  -- sha256('user')
);

INSERT INTO users (login, password, firstName, lastName, `rank`, class_id) VALUES
(
  '8c6976e5b5410415bde908bd4dee15dfb167a9c873fc4bb8a81f6f2ab448a918',
  '$2y$10$nJuUZDnjQ0o4U/7SzidHH.ch2zSvbZMKxJgLODyWYxUCujCRWb8kq',
  'Admin', 'Demo', 'admin', 2
),
(
  '04f8996da763b7a969b1028ee3007569eaf3a635486ddab211d512c85b9df8fb',
  '$2y$10$htGPlWp453L.sLUBCiGIl.FY41j.61uG03jVYuH80cbUwdap5kwhO',
  'User', 'Demo', 'utilisateur', 2
);

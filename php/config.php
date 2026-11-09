<?php
// Configuración SMTP. NO subas este archivo a repositorios públicos.
// Gmail: activa verificación en 2 pasos y crea una "Contraseña de aplicación" en
// https://myaccount.google.com/apppasswords
return [
  'host' => 'ssl://smtp.gmail.com',
  'port' => 465,
  'user' => 'abraham.martinezws@gmail.com',
  'pass' => 'PEGA_AQUI_TU_CONTRASENA_DE_APLICACION',
  'to'   => 'abraham.martinezws@gmail.com',
  'name' => 'Sitio web Abraham Martínez',
];

<?php
  //Для хостинга
  R::setup( 'mysql:host=localhost;dbname=itgenby_cert','itgenby_admin', 'OZ9Iezae' );
  //Для локалки
  // R::setup( 'mysql:host=localhost;dbname=quest02','root', '' );
  if (!R::testConnection())
  //если не связались с бд, то кидаем редирект на 404
  {
    Route::ErrorPage404();
  }

?>
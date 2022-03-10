<style>
  body {
    font-family: Arial;
  }
</style>
<div class="wrapper" style="text-align: center">
  <h2 id="weit" style="background-color: #ffc2c2; color: #800000; padding: 20px; display: none;">Подождите. Идёт
    загрузка данных и
    генерация
    сертификатов. Это
    может занять несколько минут. <br> Не покидайте страницу!
    <br>
    <img src="img/load.gif" alt="" width=100>
  </h2>
  <h1 style="text-align: center">Массовая выдача сертификатов за мероприятие</h1>
  <?php
  if (!isset($data['array'])) { ?>
  <form action="/mass" method="post" enctype="multipart/form-data">
    <label><b>Название мероприятия:</b><br>(не будет отображаться в сертификате)<br><input type="text" name="event"
        required style="width:300px"></label><br><br>
    <label>Id google-таблицы:<br><input type="text" name="table_id" required style="width:300px"></label>
    <br><br><label><b>Дата выдачи:</b><br>
      <input type="date" name="date" required style="width:300px; text-align: center"></label>
    <br><br><label><b>Текст сертификата:</b><br>
      (для вставки имени и фамилии используйте {name})
      <br><input type="text" name="text1" style="width:300px; text-align: center" value="награждается"></label>
    <br><input type="text" name="text2" style="width:300px; text-align: center" value="{name}">
    <br><input type="text" name="text3" style="width:300px; text-align: center" value="за участие в олимпиаде">
    <br><br>
    <label><b>Загрузите шаблон для русской версии</b>
      <input type="file" name="template" accept="image/*,image/png" required></label>
    <br>
    <br><br><label><b>The text of the certificate in English:</b><br>
      <br><input type="text" name="text1_en" style="width:300px; text-align: center" value="{name}"></label>
    <br><input type="text" name="text2_en" style="width:300px; text-align: center" value="is awarded">
    <br><input type="text" name="text3_en" style="width:300px; text-align: center"
      value="for participation in the Olympiad">
    <br>
    <br>
    <label><b>The template for the English version</b>
      <input type="file" name="template_en" accept="image/*,image/png" required></label>
    <br><br><br>
    <label><b>Выберите цвет текста</b></label><br>
    <label>имя:
      <input type="color" name="name_color" value="#000000"></label>
    <label>основной текст:
      <input type="color" name="label_color" value="#000000"></label>
    <label>дата и номер:
      <input type="color" name="small_color" value="#000000"></label>
    <br><br>
    <label><b>Выберите шрифт </b>
      <select name="font">
        <option value="Proxima-Nova">Proxima-Nova</option>
        <option value="Comfortaa">Comfortaa</option>
        <option value="Georgia">Georgia</option>
      </select></label>
    <br><br>
    <label><b>Введите текст Email сообщения, в котором будет отправлен сертификат</b><br><br>
      <input type="text" id="mailText" name="mail_text" style="width:650px; text-align: center"
        value="Спасибо за участие в олимпиаде онлайн-школы Айтигенио! Скачайте ваш сертификат по кнопке"></label>
    <br><br>
    <input type="submit" id="subBtn" value="Загрузить данные">
  </form>

  <?php
  } else {?>
  <br><a href="certificates.zip" download id="download" style="display:none"><button>Скачать всё ZIP(PNG)</button></a>
  <button id="send-all">Отправить всё</button><br><br>

  <table border="1" cellpadding=10 align="center">
    <tr>
      <th>Фамилия</th>
      <th>Имя</th>
      <th>Язык</th>
      <th>E-mail</th>
      <th>Выдача</th>
    </tr>
    <?php
    $n = 0;
    foreach ($data['array'] as $value) {
        $n++;
        echo '<tr>
        <td>'.$value[0].'</td>
        <td>'.$value[1].'</td>
        <td>'.$value[2].'</td>
        <td class="email">'.$value[3].'</td>
        <td><a href="'.$data['src_for_show'].'&ln='.$value[0].'&fn='.$value[1].'&l='.$value[2].'&n='.$n.'" target="_blank">Посмотреть</a> <br>
        <a href="'.$data['src_for_dovnload'].'&ln='.$value[0].'&fn='.$value[1].'&l='.$value[2].'&n='.$n.'" class="download" download="certificate_'.sprintf("%'.05d", $n).'">Скачать PNG</a> <a href="'.$data['src_for_dovnload'].'&ln='.$value[0].'&fn='.$value[1].'&l='.$value[2].'&n='.$n.'" class="send" id="'.($n-1).'">Отправить</a>  </td>
      </tr>';
    }
  }
  ?>
  </table>

</div>
<div class="wrapper">
	<div class="header">
		<div class="headerwrapper">
			<img src="img/logo.png" alt="LOGO">
			<span class="title">Сертификаты</span>
		</div>
		<a href="/mass" class="greenBtn massBtn">Массовая&nbspвыдача</a>
	</div>

	<div class="content row-flex">
		<div class="leftside">

			<form action="#" id="generateCer">
				<div class="row-flex">
					<div class="column">
						<span class="text">Тип</span><br>
						<input type="radio" class="custom-radio" id='t1' name='typecert' value="gift"
							onchange="toggleCert(this); checkParams();">
						<label for="t1">Подарочный</label><br>

						<input type="radio" class="custom-radio" id='t2' name='typecert' value="total"
							onchange="toggleCert(this); checkParams();">
						<label for="t2">Итоговый</label><br>

						<input type="radio" class="custom-radio" id='t3' name='typecert' value="middle"
							onchange="toggleCert(this); checkParams();">
						<label for="t3">Промежуточный</label>
					</div>
					<div class="column left-space">
						<span class="text">Язык</span><br>
						<input type="radio" class="custom-radio" id='lang1' name='lang' checked="checked" value="ru">
						<label for="lang1">Рус</label><br>

						<input type="radio" class="custom-radio" id='lang2' name='lang' value="en">
						<label for="lang2">Eng</label>
					</div>
					<div class="column left-space total-column" style="display:none">
						<span class="text">Пол</span><br>
						<input type="radio" class="custom-radio" id='m' name='gender' checked="checked" value="m">
						<label for="m">M</label><br>

						<input type="radio" class="custom-radio" id='f' name='gender' value="f">
						<label for="f">Ж</label>
					</div>
					<div class="column left-space gift-column">
						<span class="text">Формат занятий</span><br>
						<input type="radio" class="custom-radio" id='igf' name='form' checked="checked" value="igf">
						<label for="igf">Индивидуально-групповой</label><br>

						<input type="radio" class="custom-radio" id='gf' name='form' value="gf">
						<label for="gf">Групповой</label>
						
						<input type="radio" class="custom-radio" id='if' name='form' value="if">
						<label for="if">Индивидуальный</label>
					</div>

				</div>

				<div class="field">
					<span>Фамилия</span><br>
					<input type="text" placeholder="Введите фамилию" id="lastname" onkeyup="checkParams()"
						autocomplete="off">
				</div>

				<div class="field">
					<span>Имя</span><br>
					<input type="text" placeholder="Введите имя" id="firstname" onkeyup="checkParams()"
						autocomplete="off">
				</div>

				<div class="field">
					<fieldset id="gift" class="item gift">
						<div class="field">
							<span>Количество занятий</span><br>
							<input type="text" placeholder="30" id="countLesson"
								onkeypress="return (event.charCode >= 48 && event.charCode <= 57 && /^\d{0,3}$/.test(this.value));"
								onkeyup="checkParams();" autocomplete="off">
						</div>

						<div class="field">
							<span>Длительность</span><br>
							<select name="duration" id="duration">
								<option value="120" selected>120</option>
								<option value="60">60</option>
							</select>

						</div>
					</fieldset>
				</div>

				<div class="field">
					<fieldset id="total" class="item total middle">
						<div class="field">
							<span>Направление</span><br>
							<select name="duration" id="subject" onkeyup="checkParams();" onchange="checkParams();">
								<?php
                                        foreach ($data['subjects'] as $subj) {
                                            echo '<option value="'.$subj['id'].'"';
                                            if ($subj['title'] == 'Scratch') {
                                                echo ' selected';
                                            }
                                            if ($subj['title_en']) {
                                                echo ' data_en="'.$subj['title_en'].'"';
                                            } else {
                                                echo ' data_en=""';
                                            }
                                            echo '>'.$subj['title'].'</option>';
                                        }
                                ?>
							</select>
						</div>

						<div class="field">
							<span>Количество часов</span><br>
							<input type="text" placeholder="30" id="countHours"
								onkeypress="return (event.charCode >= 48 && event.charCode <= 57 && /^\d{0,3}$/.test(this.value));"
								onkeyup="checkParams()" autocomplete="off">
						</div>
					</fieldset>
				</div>


				<div class="field">
					<span>Дата выдачи</span><br>
					<input type="text" placeholder="Введите дату" class="datepicker-here" data-position="right top"
						id="dateCert" onkeyup="checkParams();" onchange="checkParams();">
				</div>

				<div class="field">
					<span>Номер/id семьи</span><br>
					<input type="text" placeholder="xxxxxxx" id="idFamily" oninput="checkParams()"
						onkeyup="checkParams()" autocomplete="off">
				</div>

				<div class="field">
					<span>Шаблон</span><br>
					<div class="template">
						<?php
                                foreach ($data['templates'] as $temp) {
                                    $file = $temp['template'];
                                    if (!isset(explode("_", $file)[2]) && isset(explode(".", $file)[1]) && $file != 'temporary_en.png' && $file != 'temporary.png') {
                                        echo '<div data-file="'.$file.'" data-category="'.explode("_", $file)[0].'"><img src="img/templates/100/'.$file.'" alt=""></div>';
                                    }
                                }
                        ?>
					</div>
				</div>
				<br><br>

				<div class="row-flex space-between flex-end">
					<div class="column email">
						<div class="field">
							<span>Отправить email</span><br>
							<input type="text" placeholder="Введите email" id="email" onkeyup="checkBtns()"
								autocomplete="off">
						</div>
					</div>
					<div class="column btns">
						<input type="submit" value="Показать" class="greenBtn" id="show" disabled>
						<input type="submit" value="Скачать PNG" class="greenBtn" id="download" disabled><br>
						<input type="submit" value="Отправить" id="sendToEmail" disabled>
					</div>

				</div>

		</div>
		</form>

		<div class="rightside">
			<?php
          echo '<img id="certificate" src="img/templates/500/'.$data['files'][0].'" alt="">'
      ?>
		</div>
	</div>


</div>
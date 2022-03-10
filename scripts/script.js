let slidesHTML = [];
let activCert = {};
let activCategory = 'all';
let activLang = 'ru';
let activGender = 'm';
let activForm = 'igf';
let srcForDownload = '';
let generated = false;
let certId = 0;

/*   Функция для активации слайдера   */
function sliderActivate( selector, slidesToShow = 3 ) {
  $( selector ).slick( {
    slidesToShow: slidesToShow,
    slidesToScroll: 1,
    // autoplay: true,
    autoplaySpeed: 2000,
  } );
}

/*   Функция для перезагрузки слайдера   */
function sliderReload( selector ) {
  let temp = document.querySelector( selector );
  //убираем с элемента лишние классы и обнуляем html
  temp.setAttribute( 'class', selector.split( '.' )[ 1 ] );
  temp.innerHTML = "";
  //заполняем элемент новыми данными
  let s = 0;
  for ( let sl of slidesHTML ) {
    if ( sl.category == activCategory || activCategory == 'all' ) {
      let elem = document.createElement( 'div' );
      elem.innerHTML = sl.html;
      elem.setAttribute( "data-category", sl.category )
      elem.setAttribute( "data-file", sl.file )
      temp.append( elem );
      s++;
    }
  }
  if (s > 3) s = 3; 
  //повторно активируем слайдер
  sliderActivate( selector, s );
  //меняем язык шаблонов в слайдере и выбираем активный шаблон
  const slides = document.querySelectorAll( '.slick-slide' );
  for ( let slide of slides ) {
    let img = slide.childNodes[ 0 ];
    let f = img.getAttribute( 'src' ).split( '/' )[ 3 ];
    f = f.replace( '_en' );
    if ( f == activCert.file ) {
      img.parentNode.classList.add( 'selected' )
    }
    if ( activLang == 'en' ) {
      let f = img.getAttribute( 'src' ).split( '.' );
      img.setAttribute( 'src', f[ 0 ] + '_en.' + f[ 1 ] );
    }
  }
}

/* Функция показа отображения шаблона */
function showTemplate() {
  const certificate = document.getElementById( 'certificate' );
  certificate.style.width = '30px';
  certificate.setAttribute( 'src', `slick/ajax-loader.gif` );
  let file;
  if ( activLang == 'en' ) {
    let f = activCert.file.split( '.' );
    file = f[ 0 ] + '_en.' + f[ 1 ];
  } else {
    file = activCert.file;
  }
  let img = document.createElement( 'img' );
  img.src = `img/templates/500/${file}`;

  img.onload = function () {
    certificate.setAttribute('src', '');
    certificate.style.width = '';
    certificate.setAttribute( 'src', `img/templates/500/${file}` );
  };

}

/* СТРАНИЦА ЗАГРУЖЕНА  */
$( document ).ready( function () {
  
  document.addEventListener( 'submit', e => e.preventDefault() );
  /* Запоминаем код слайдов с шаблонами. Это надо для того, чтобы удалять их и вставлять при переключении типа сертификата*/
  let imgs = [];
  let certs = document.querySelectorAll( '.template>div' );
  for ( cert of certs ) {
    slidesHTML.push( {
      category: cert.dataset.category,
      html: cert.innerHTML,
      file: cert.dataset.file
    } );
  }
  activCert = {
    file: slidesHTML[ 0 ].file,
    category: slidesHTML[ 0 ].category,
    lang: "ru"
  }

  //активируем слайдер
  sliderActivate( '.template' );

  //выбираем категорию по умолчанию
  $('#t1').trigger('change');
  document.getElementById("t1").setAttribute("checked", "checked");

  //функция для подсветки текущего шаблона
  const selectSlide = function(file) {
    const sls = document.querySelectorAll( '.template .slick-slide' );
    const reset = document.querySelectorAll( '.selected' );
    if (reset) {
      for (r of reset) {
        r.classList.remove( 'selected' );
      }
    }
    for (s of sls) {
      if (s.dataset.file == file) {
        s.classList.add('selected');
      }
    }
  }
 
  //устанавливаем выбранный шаблон по умолчанию
  const active = document.querySelector('.slick-active');
  selectSlide(active.dataset.file)

  /* событие выбора шаблона */
  document.querySelector( '.template' ).addEventListener( 'click', ( e ) => {
    isShowed = false;
    checkBtns();
    const target = e.target;
    if ( target.parentNode.dataset.category ) {
      e.preventDefault();
      selectSlide(target.parentNode.dataset.file)
      activCert = {
        file: target.parentNode.dataset.file,
        category: target.parentNode.dataset.category,
        lang: activCert.lang
      }
      showTemplate();
    }
  } )

  /* Событие выбора языка */
  const radioLeng = document.querySelectorAll( 'input[name="lang"]' );
  for ( rad of radioLeng ) {
    rad.addEventListener( 'change', ( e ) => {
      const target = e.target;
      if ( target.value != activLang ) {
        activLang = target.value;
        activCert.lang = activLang;
        showTemplate();
        sliderReload( '.template' );
      }
    } )
  }

  /* Событие выбора пола */
  const radioGend = document.querySelectorAll( 'input[name="gender"]' );
  for ( rad of radioGend ) {
    rad.addEventListener( 'change', ( e ) => {
      const target = e.target;
      if ( target.value != activGender ) {
        activGender = target.value;
        showTemplate();
        sliderReload( '.template' );
      }
    } )
  }
  
  /* Событие выбора формата обучения */
  const radioForm = document.querySelectorAll( 'input[name="form"]' );
  for ( rad of radioForm ) {
    rad.addEventListener( 'change', ( e ) => {
      const target = e.target;
      if ( target.value != activForm ) {
        activForm = target.value;
      }
    } )
  }

    /* Клик по кнопке Показать */

  $( '#show' ).click( function () {
    isShowed = true;
    generated = false;
    if ( isShowed ) {
      checkBtns();
    }
    showSertificate();
  } );

  /* Клик по кнопке Скачать */
  $('#download').click(function () {
    let link = document.createElement('a');
    //генерируем сертификат и добавляем в базу данных, если ещё не добавлен
    if (!generated) {
      generated = true;
      sendXML(`generate?${srcForDownload}`,(result)=>{
        certId = result;
        link.setAttribute('href', 'img/certificate.php?'+srcForDownload+'&cert_id='+certId);
        link.setAttribute('download',certId);
        link.click();
      }, (err)=> {
        alert(err);
        generated = false;
      });
    } else {
      link.setAttribute('href', 'img/certificate.php?'+srcForDownload+'&cert_id='+certId);
      link.setAttribute('download',certId);
      link.click();
    }
  });

    /* Клик по кнопке Отправить */
    $('#sendToEmail').click(function () {
      let email = $('#email').val();
      let link = document.createElement('a');
      //генерируем сертификат и добавляем в базу данных, если ещё не добавлен
      let body;
      if (!generated) {
        generated = true;
        sendXML(`generate?${srcForDownload+'&mail='+email}`,(result)=>{
          certId = result;
          body = `<p style="text-align: center"><a href="http://cert.itgen.io/img/certificate.php?${srcForDownload}&cert_id=${certId}" download style="background-color:#50965C; color: #fff; width: 125px; font-size: 14px; border-radius: 5px; padding: 8px 16px; display: block; margin: 20px auto; text-align: center; text-decoration: none">Скачать сертификат PNG</a><br><img src="http://cert.itgen.io/img/certificate.php?${srcForDownload}&cert_id=${certId}" width="600"></p>`;
          sendEmail(email, body)
        }, (err)=> {
          alert(err);
          generated = false;
        });
      } else {
        //сохраняем email в бвзу данных
        sendXML(`savemail?mail=${email}&id=${certId}`,(result)=>{
          body = `<p style="text-align: center"><a href="http://cert.itgen.io/img/certificate.php?${srcForDownload}&cert_id=${certId}" download style="background-color:#50965C; color: #fff; width: 125px; font-size: 14px; border-radius: 5px; padding: 8px 16px; display: block; margin: 20px auto; text-align: center; text-decoration: none">Скачать сертификат PNG</a><br><img src="http://cert.itgen.io/img/certificate.php?${srcForDownload}&cert_id=${certId}" width="600"></p>`;
          sendEmail(email, body)
        }, (err)=> {
          alert(err);
        });
      }
    })

} );

var isShowed = false;

/* Устанавливаем дату по умолчанию */
$( function () {
  $( "#dateCert" ).datepicker( {
    dateFormat: 'dd.mm.yyyy'
  } ).val( getTodaysDate( 0 ) ); // For current date

} );

/* Функции для проверки данных на пустоту */
function checkBtns() {
  if ( isShowed ) {
    $( '#download' ).removeAttr( 'disabled' );

    var email = $( '#email' ).val();
    if ( email.length != 0 ) {
      $( '#sendToEmail' ).removeAttr( 'disabled' );
    } else {
      $( '#sendToEmail' ).attr( 'disabled', 'disabled' );
    }
  } else {
    $( '#sendToEmail' ).attr( 'disabled', 'disabled' );
    $( '#download' ).attr( 'disabled', 'disabled' );
  }
}
function checkParams() {
  isShowed = false;
  checkBtns();
  var tcert = $( 'input[name=typecert]:checked' ).val();
  var firstname = $( '#firstname' ).val();
  var lastname = $( '#lastname' ).val();
  var date = $( '#dateCert' ).val();
  var idF = $( '#idFamily' ).val();
  if ( firstname.length != 0 && lastname.length != 0 && date.length == 10 && idF.length != 0 ) {
    var countLessons = $( '#countLesson' ).val();
    var countOfHours = $( '#countHours' ).val();
    if ( ( activCategory == 'gift' && countLessons.length != 0 ) || ( (activCategory == 'total' || activCategory == 'middle') && countOfHours.length != 0 ) ) {
      $( '#show' ).removeAttr( 'disabled' );
    } else {
      $( '#show' ).attr( 'disabled', 'disabled' );
    }
  } else {
    $( '#show' ).attr( 'disabled', 'disabled' );
  }
}

/* Получение текущей даты */
function getTodaysDate( val ) {
  var t = new Date,
    day, month, year = t.getFullYear();
  if ( t.getDate() < 10 ) {
    day = "0" + t.getDate();
  } else {
    day = t.getDate();
  }
  if ( ( t.getMonth() + 1 ) < 10 ) {
    month = "0" + ( t.getMonth() + 1 - val );
  } else {
    month = t.getMonth() + 1 - val;
  }

  return ( day + '.' + month + '.' + year );
}

/* Функция для события выбора категории сертификата */
function toggleCert( rad ) {
  var type = rad.value;

  //показываем поля формы, соответствующие категории сертификата
  for ( var k = 0, elm; elm = rad.form.elements[ k ]; k++ ) {
    if ( elm.classList.contains('item') ) {
      elm.style.display = elm.classList.contains(type) ? 'inline' : '';
    }
  }
  if (rad.value == "total") {
    document.querySelector(".total-column").style.display = "";
  } else {
    document.querySelector(".total-column").style.display = "none";
  }
  if (rad.value == "gift") {
        document.querySelector(".gift-column").style.display = "";
  } else {
    document.querySelector(".gift-column").style.display = "none";
  }

  if ( activCategory != rad.value ) {
    activCategory = rad.value;
    activCert.category = activCategory;
    let slide = slidesHTML.find( slide => slide.category == rad.value );
    activCert.file = slide.file;
    //перезагружаем слайдер для обновления данных
    sliderReload( '.template' );
    //показываем первый шаблон сертификата соответсвующей категории
    showTemplate();
  }
}

/*  Отправка запроса  */
const sendXML = ( url, endCallback, errorCallback) => {
  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function () {
    if ( this.readyState == 4 ) {
      if ( this.status == 200 ) {
        endCallback( xhttp.response );
      } else {
        errorCallback( "Ошибка" );
      }
    }
  };
  xhttp.responseType = "text";
  xhttp.open( "GET", url, true );
  xhttp.send();
}

function showSertificate() {
  var firstname = $( '#firstname' ).val();
  var lastname = $( '#lastname' ).val();
  var gender = activGender;
  var date = $( '#dateCert' ).val();
  var subj = '0';
  var count = '0';
  var duration = '0';
  if (activCategory == 'gift') {
    count = $( '#countLesson' ).val();
    duration = $( '#duration' ).val();
  } else {
    subj = $( '#subject' ).val();
    count = $( '#countHours' ).val();
  }
  var family = $( '#idFamily' ).val();
  var file = activCert['file'];
  let srcForShow = `img/certificate.php?type=min&cat=${activCategory}&l=${activLang}&f=${file}&fn=${firstname}&ln=${lastname}&d=${date}&fam=${family}&dur=${duration}&c=${count}&s=${subj}&g=${activGender}&fm=${activForm}`;
  srcForDownload = `type=max&cat=${activCategory}&l=${activLang}&f=${file}&fn=${firstname}&ln=${lastname}&d=${date}&fam=${family}&dur=${duration}&c=${count}&s=${subj}&g=${activGender}&fm=${activForm}`;
  
  document.getElementById("certificate").setAttribute("src", srcForShow);
  
}

function sendEmail(email, text) {
  const data = JSON.stringify({
    "personalizations": [
      {
        "to": [
          {
            "email": email
          }
        ],
        "subject": "Сертификат"
      }
    ],
    "from": {
      "email": "itgenby@gmail.com"
    },
    "content": [
      {
        "type": "text/html",
        "value": text
      }
    ]
  });
  
  const xhr = new XMLHttpRequest();
  xhr.withCredentials = true;
  
  xhr.addEventListener("readystatechange", function () {
    if (this.readyState === this.DONE) {
      alert('Сообщение отправлено')
    }
  });
  
  xhr.open("POST", "https://rapidprod-sendgrid-v1.p.rapidapi.com/mail/send");
  xhr.setRequestHeader("content-type", "application/json");
  xhr.setRequestHeader("x-rapidapi-key", "50dfad0173msh42e8185293fd30cp1bc7f6jsn2e676834e67f");
  xhr.setRequestHeader("x-rapidapi-host", "rapidprod-sendgrid-v1.p.rapidapi.com");
  
  xhr.send(data);
}


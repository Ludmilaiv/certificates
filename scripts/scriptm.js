
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

//отправка сообщения
function sendEmail(email, text, callback) {
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
      callback();
    }
  });
  
  xhr.open("POST", "https://rapidprod-sendgrid-v1.p.rapidapi.com/mail/send");
  xhr.setRequestHeader("content-type", "application/json");
  xhr.setRequestHeader("x-rapidapi-key", "50dfad0173msh42e8185293fd30cp1bc7f6jsn2e676834e67f");
  xhr.setRequestHeader("x-rapidapi-host", "rapidprod-sendgrid-v1.p.rapidapi.com");
  
  xhr.send(data);
}

document.addEventListener("DOMContentLoaded", ()=> {

  const forma = document.forms[0];
  const subBtn = document.getElementById("subBtn");
  //const downloadBtn = document.getElementById("download");
  const table = document.querySelector('table');
  const sendAllBtn = document.getElementById("send-all");
  const links = document.querySelectorAll(".download");
  const emails = document.querySelectorAll(".email");
  const weit = document.getElementById("weit");
  
  if (forma) {
    forma.addEventListener("submit", function(e){
      e.preventDefault();
      subBtn.disabled = "disabled";
      scroll(0,0);
      weit.style.display = "block";
      localStorage.setItem('mailtext', document.getElementById("mailText").value);
      forma.submit();
    })
  }
  

  if (table) {

    const mailText = localStorage.getItem('mailtext');

    table.addEventListener('click', (event) => {
      const target = event.target;
      if (target.classList.contains("send")) {
        event.preventDefault();
        const link = target.href;
        const i = +target.id;
        var body = `<h2 style=""text-align: center">${mailText}</h2><p style="text-align: center"><a href="${link}" download style="background-color:#50965C; color: #fff; width: 125px; font-size: 14px; border-radius: 5px; padding: 8px 16px; display: block; margin: 20px auto; text-align: center; text-decoration: none">Скачать сертификат PNG</a><br><img src="${link}" width="600"></p>`;
        sendEmail(emails[i].innerHTML, body, ()=>{alert("Сообщение отправлено")});
      }
    })
  
  sendAllBtn.addEventListener("click", function(){

    let n = 0;
    function countMail() {
      n++;
      if (n == links.length) {
        alert("Сообщения отправлены");
      }
    }
    for (var i = 0; i < links.length; i++) {
      console.log(links[i].href);
      var body = `<h2 style=""text-align: center">${mailText}</h2><p style="text-align: center"><a href="${links[i].href}" download style="background-color:#50965C; color: #fff; width: 125px; font-size: 14px; border-radius: 5px; padding: 8px 16px; display: block; margin: 20px auto; text-align: center; text-decoration: none">Скачать сертификат PNG</a><br><img src="${links[i].href}" width="600"></p>`;
      sendEmail(emails[i].innerHTML, body, countMail);
      
    }
})

  }



});


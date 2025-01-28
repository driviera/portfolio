var active = 0;
function getNext() {
  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      const obj = JSON.parse(this.responseText);
      active = obj.active;
      str = obj.data;
      console.log("%c" + str, "color:#e1e1e1;text-shadow: 1px 1px 0px green;font-size:24px;");
      if (str.startsWith('url')) {
        location.href = str.substring(4);
      } else {
        $('#marquee').html(str);
        $('header').fadeIn(1000);
        setTimeout(function () {
          $('header').fadeOut(1000);
        }, 2000);
      }
    }
  };
  xhttp.open("GET", "/?action=next&active=" + active, true);
  xhttp.send();
}
getNext();
setInterval(function () {
  getNext();
}, 3000);

console.log('%cWelcome to the Dev Tools!', 'color:red;font-size:2rem;font-family:monospace;');
let orderbyimg = true; //Change the setting picture when pressed
var button = document.getElementById("settings-button");
var img = document.getElementById("gear-icon");
    button.addEventListener("click", (event) =>{
        orderbyimg = !orderbyimg;
      if(orderbyimg == false){
        img.src = "Media/clear_24px.png";
    } else{
        img.src = "Media/settings_24px.png";
    }});
  
//toggle the settings menu
let dropDown = document.getElementById('settings-button');
let dworpnown = document.querySelector('.dropdown-wrapper');
dropDown.onclick = function() {
    dworpnown.classList.toggle('active');
}

//toggle the help popup window
function myFunction() {
  var popup = document.getElementById("myPopup");
  popup.classList.toggle("show");
}

//google translate
function googleTranslateElementInit() {
  new google.translate.TranslateElement({pageLanguage: 'en'}, 'google_translate_element');
}



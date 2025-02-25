//check if darkmode is on or off
window.onload=function color(){
    var light = localStorage.getItem('dark')
    if(light == 'true') {
        darkmode.apply();
    }
}

//Function to get dark mode
function darkmode() {
    const root = document.querySelector(':root');
    root.style.setProperty('--background-color', '#3C3C3C');
    const color = getComputedStyle(root).getPropertyValue('--background-color');
    let drop_cont = document.getElementById('drop-content');
    drop_cont.style.backgroundColor = "#636363";
    let text = document.getElementById('settings-grid-item-4');
    text.style.color = "white";
    let text2 = document.getElementById('settings-grid-item-2');
    text2.style.color = "white";
    let text3 = document.getElementById('settings-subtitle-app');
    text3.style.color = "white";

    let text4 = document.getElementById('settings-heading');
    text4.style.color = "white";

    let text5 = document.getElementById('settings-subtitle-lang');
    text5.style.color = "white";

    let searchbar = document.getElementById('1');
    searchbar.style.backgroundColor = "#636363";

    let searchbar2 = document.getElementById('12345');
    searchbar2.style.backgroundColor = "#636363";

    let help = document.getElementById('myPopup');
    help.style.backgroundColor = "#636363";
    help.style.color = "#E2E2E2";
    let help2 = document.getElementById('help');
    help2.style.borderBottomColor = "#3C3C3C";

    let setsheadline = document.getElementById('sets_headline');
    setsheadline.style.color = "#E2E2E2";

    localStorage.setItem('dark', true); //store if the user presses darkmode
}

//Function to get light mode
function lightmode() {
    const root = document.querySelector(':root');
    root.style.setProperty('--background-color', '#F4F7FE');
    const color = getComputedStyle(root).getPropertyValue('--background-color');
    let drop_cont = document.getElementById('drop-content');
    drop_cont.style.backgroundColor = "white";
    let text = document.getElementById('settings-grid-item-4');
    text.style.color = "#575757";
    let text2 = document.getElementById('settings-grid-item-2');
    text2.style.color = "#575757";
    let text3 = document.getElementById('settings-subtitle-app');
    text3.style.color = "#575757";
    let text4 = document.getElementById('settings-heading');
    text4.style.color = "#575757";
    let text5 = document.getElementById('settings-subtitle-lang');
    text5.style.color = "#575757";

    let searchbar = document.getElementById('1');
    searchbar.style.backgroundColor = "white";

    let searchbar2 = document.getElementById('12345');
    searchbar2.style.backgroundColor = "white";
    searchbar2.style.color = "black";

    let help = document.getElementById('myPopup');
    help.style.backgroundColor = "white";
    help.style.color = "black";
    let help2 = document.getElementById('help');
    help2.style.borderBottomColor = "lightgrey";
    let setsheadline = document.getElementById('sets_headline');
    setsheadline.style.color = "#575757";
    localStorage.setItem('dark', false);//store if the user presses lightmode
}

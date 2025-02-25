
window.onload=function color(){
    var light = localStorage.getItem('dark')
    if(light == 'true'){
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


    let searchbar2_set_content = document.getElementById('search-text');
    searchbar2_set_content.style.backgroundColor = "#636363";
    searchbar2_set_content.style.color = "white";


    let searchform = document.getElementById('search_form');
    searchform.style.backgroundColor = "#636363";


    let help = document.getElementById('myPopup');
    help.style.backgroundColor = "#636363";
    help.style.color = "#E2E2E2";
    let help2 = document.getElementById('help');
    help2.style.borderBottomColor = "#3C3C3C";
    localStorage.setItem('dark', true);
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


    let searchbar2_set_content = document.getElementById('search-text');
    searchbar2_set_content.style.backgroundColor = "white";
    searchbar2_set_content.style.color = "black";


    let searchform = document.getElementById('search_form');
    searchform.style.backgroundColor = "white";


    let help = document.getElementById('myPopup');
    help.style.backgroundColor = "white";
    help.style.color = "black";
    let help2 = document.getElementById('help');
    help2.style.borderBottomColor = "lightgrey";
    localStorage.setItem('dark', false);
}


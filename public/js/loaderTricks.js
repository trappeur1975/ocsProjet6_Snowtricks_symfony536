let cardTick = document.querySelector('#zoneTricks div.col-sm-4');

loader = document.getElementById('loadMoreTrick');
let zoneTricks = document.getElementById('zoneTricks');

let offset = 3;
let clickedButton= 0;

loader.addEventListener('click', function(event) {
    event.preventDefault();
    clickedButton++;

    fetch('/loadTricks/'+offset*clickedButton)
    .then(function(res) {
        return res.text();
    })
    .then(function(value){
        zoneTricks.innerHTML += value;
    })
});
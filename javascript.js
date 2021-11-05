document.addEventListener("DOMContentLoaded", function(){
    var counter = 0;
    let btn = document.getElementById('addCurrency');
    let listInputs = document.getElementsByClassName('currName');
    let lastID = listInputs[listInputs.length - 1].id;



    btn.addEventListener('click', function(e) {
        e.preventDefault();
        let lastNumInput = parseInt(lastID[lastID.length - 1]) + 1 + counter;
        let stringName = 'currName' + lastNumInput ;
        let stringRate = 'currRate' + lastNumInput ;
        console.log(stringRate);

        let stringHTML = '<div class="currency">\n' +
            '                        <label for="'+ stringName +'">Currency Name:</label>\n' +
            '                        <input type="text" id="'+ stringName +'" name="'+ stringName +'"><br><br>\n' +
            '                        <label for="'+ stringRate +'">Exchange rate:</label>\n' +
            '                        <input type="number" pattern="[0-9]+([\\.,][0-9]+)?" step="0.001" id="'+ stringRate +'" name="'+ stringRate +'"><br><br>\n' +
            '                    </div>';

        //let listCurr = ;
        //listCurr.innerHTML += stringHTML;
        var child = document.createElement('div');
        child.innerHTML = stringHTML;
        child = child.firstChild;
        document.getElementById('listCurr').appendChild(child);
        counter++;
        console.log(counter);
    }, false);
});
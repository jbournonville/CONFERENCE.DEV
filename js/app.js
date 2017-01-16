/* Fonction pour déclarer l'utilisation de AJAX*/
var xhr = creeXHR_Object();
function creeXHR_Object() {
    var xhr = null;
    try {
        xhr = new XMLHttpRequest();
    }
    catch (Error) {
        try {
            xhr = new ActiveXObject("Msxml2.XMLHTTP");
        }
        catch (Error) {
            try {
                xhr = new ActiveXObject("Microsoft.XMLHTTP");
            }
            catch (Error) {
                alert(" impossible de créer l'objet XMLHttpRequest")
            }
        }
    }
    return xhr;
}


function showSlots(idEvent) {
    xhr.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("slots").innerHTML =
                this.responseText;
        }
    };

    xhr.open('GET', 'configSlots.php?eventId=' + idEvent, true);
    xhr.send();
}

function showNewSessionBlock(idEvent, idSlot) {
    xhr.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {

            var element = document.getElementsByClassName("newBlock");
            var nb = element.length;
            for (var i = 1; i < nb + 1; i++) {
                var block = "newBlockSession" + i;
                if (i != idSlot) {
                    document.getElementById(block).setAttribute("hidden", 'true');
                } else {
                    document.getElementById(block).innerHTML = this.responseText;
                }
            }
        }
    };
    xhr.open('GET', 'configSession.php?eventId=' + idEvent + '&slotId=' + idSlot, true);
    xhr.send();
}

function updateDesc(block, idSession) {
    xhr.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            block.getElementsByClassName('desc')[0].innerHTML = this.responseText;
        }
    };
    xhr.open('GET', 'innerDescription.php?idSession=' + idSession, true);
    xhr.send();
}

function updateUserSessions(element) {
    var idSession = element.value;
    var block = element.parentElement;
    var i, j, k;
    var select = document.getElementsByClassName('sessionSelection');

    var selectedOption = [];
    var option;

    updateDesc(block, idSession);

    for (i = 0; i < select.length; i++) {
        option = select[i].options[select[i].selectedIndex].text;
        if (option != "") {
            selectedOption.push(option);
        }
    }

    for (i = 0; i < select.length; i++) {
        option = select[i].options;
        for (j = 0; j < option.length; j++) {
            if (option[j].innerHTML != "") {
                option[j].disabled = false;
                for (k = 0; k < selectedOption.length; k++) {
                    if (option[j].innerHTML == selectedOption[k]) {
                        if (select[i].selectedIndex != j) {
                            option[j].disabled = true;
                        }
                    }
                }
            }
        }
    }
}

function updateModuleSessions(element) {
    var i, j, k;

    var slot = element.parentElement.parentElement.parentElement;

    var select = slot.getElementsByClassName('selectModule');

    var selectedOption = [];
    var option;

    for (i = 0; i < select.length; i++) {
        option = select[i].options[select[i].selectedIndex].text;
        if (option != "") {
            selectedOption.push(option);
        }
    }

    for (i = 0; i < select.length; i++) {
        option = select[i].options;
        for (j = 0; j < option.length; j++) {
            if (option[j].innerHTML != "") {
                option[j].disabled = false;
                for (k = 0; k < selectedOption.length; k++) {
                    if (option[j].innerHTML == selectedOption[k]) {
                        if (select[i].selectedIndex != j) {
                            option[j].disabled = true;
                        }
                    }
                }
            }
        }
    }

}

function updateHallSessions(element) {

    var i, j, k;

    var slot = element.parentElement.parentElement.parentElement;

    var select = slot.getElementsByClassName('selectHall');

    var selectedOption = [];
    var option;

    for (i = 0; i < select.length; i++) {
        option = select[i].options[select[i].selectedIndex].text;
        if (option != "") {
            selectedOption.push(option);
        }
    }

    for (i = 0; i < select.length; i++) {
        option = select[i].options;
        for (j = 0; j < option.length; j++) {
            if (option[j].innerHTML != "") {
                option[j].disabled = false;
                for (k = 0; k < selectedOption.length; k++) {
                    if (option[j].innerHTML == selectedOption[k]) {
                        if (select[i].selectedIndex != j) {
                            option[j].disabled = true;
                        }
                    }
                }
            }
        }
    }
}

function updateSpeakersSessions(element) {

    var i, j, k;

    var slot = element.parentElement.parentElement.parentElement.parentElement;

    var select = slot.getElementsByClassName('selectSpeaker');

    var selectedOption = [];
    var option;

    for (i = 0; i < select.length; i++) {
        option = select[i].options[select[i].selectedIndex].text;
        if (option != "") {
            selectedOption.push(option);
        }
    }

    for (i = 0; i < select.length; i++) {
        option = select[i].options;
        for (j = 0; j < option.length; j++) {
            if (option[j].innerHTML != "") {
                option[j].disabled = false;
                for (k = 0; k < selectedOption.length; k++) {
                    if (option[j].innerHTML == selectedOption[k]) {
                        if (select[i].selectedIndex != j) {
                            option[j].disabled = true;
                        }
                    }
                }
            }
        }
    }
}
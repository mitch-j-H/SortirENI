window.onload = () => {

    //onload clear the list and the choisir un ville option
    let thingo = document.querySelector("#event_Location");
    thingo.innerHTML = "";
    let option2 = document.createElement("option");
    option2.textContent = "Choisir une ville";
    thingo.append(option2);
    // assign value to selector
    let city = document.querySelector("#event_city");

    //onload post the details of the showing city locations automatically
    let data = city.value;

    delayingScreenUpdate(data);

    //test modal
    test();
    modalManage();

    //event listener on change of city re affiche the events
    city.addEventListener("change", function () {
        let data1 = this.value;
        delayingScreenUpdate(data1);
    });

    let location = document.querySelector("#event_Location");

    //change of location changes details
    location.addEventListener("change", function () {
        changeDetails();
    });

}

//fetch function => if I had more time would make this an await function itself
function fetchLocations(data1) {
    //fetch using get request
    fetch("http://localhost/SortirENI/public/request/locations/" + data1)
        .then(response => response.json())
        .then((data) => {
            if(data.length === 0){
                let thingo = document.querySelector("#event_Location");
                thingo.innerHTML = "";
                let option2 = document.createElement("option");
                option2.textContent = "Ajouter un lieu";
                thingo.append(option2);
            }else {
                let locationSelect = document.querySelector("#event_Location");
                locationSelect.innerHTML = "";
                //make each option and insert into DOM
                for(let i = 0; i < data.length; i++){
                    let option = document.createElement("option")
                    option.textContent = data[i].name;
                    option.setAttribute("value", data[i].id);
                    option.dataset.streetAddress = data[i].street_address;
                    option.dataset.postcode = data[i].postcode;
                    option.dataset.latitude = data[i].latitude;
                    option.dataset.longitude = data[i].longitude;
                    locationSelect.append(option)
                }
            }
        })
        .catch(error => {
            console.log(error);
        });
}

//update the screen details
function changeDetails(){
    //select all items necessary
    let selected = document.getElementById('event_Location').options[document.getElementById('event_Location').selectedIndex];
    let rue = document.getElementById('rue');
    let codePostal = document.getElementById('codePostal');
    let latitude = document.getElementById('event_latitude');
    let longitude = document.getElementById('event_longitude');
    // console.log(selected.text);
    if(selected.text !== "Ajouter un lieu" /*&& selected.text !=="Choisir une ville"*/){
        //assigning values
        rue.innerText = "rue: " + selected.dataset.streetAddress;
        codePostal.innerText = "Code Postal: " + selected.dataset.postcode;
        if(selected.dataset.latitude == "null" || selected.dataset.longitude == "null"){
            latitude.value = "";
            longitude.value = "";
        } else {
            latitude.value= selected.dataset.latitude;
            longitude.value = selected.dataset.longitude;
        }

    } else {
        rue.innerText = "";
        codePostal.innerText = "";
        latitude.value="";
        longitude.value = "";
    }
}

//added to ensure the async fetch delay does not alter changeDetails response
async function delayingScreenUpdate(data){
    fetchLocations(data);
    await new Promise(resolve => setTimeout(resolve, 500));
    changeDetails();
}

async function test(){
    const modal = document.getElementById("modal");
    const btnSkip = document.getElementById("btnSkip");
    const ajouter = document.getElementById("showModal")
    ajouter.addEventListener("click", function(event) {
        event.preventDefault();
        modal.classList.add("modal-visible");
    })
    await new Promise(resolve => setTimeout(resolve, 500));

}

function modalManage(){
    let close = document.getElementById("close");
    let modal = document.getElementById("modal");

    close.addEventListener("click", function (){
        modal.classList.remove("modal-visible");
    })

}
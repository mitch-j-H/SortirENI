window.onload = () => {
    // look for locations in city
    let location = document.querySelector("#event_city");

    location.addEventListener("change", function(){
        //select form and row plus value selected
        let form = this.closest("form");
        let data1 = /*this.name + "=" + */this.value;

        console.log(data1);

        //fetch using get request
        fetch("http://localhost/SortirENI/public/request/locations/" + data1)
            .then(response => response.json())
            .then((data) => {
                let locationSelect = document.querySelector("#event_Location");
                locationSelect.innerHTML = "";
                //make each option and insert into DOM
                for(let i = 0; i < data.length; i++){
                    let option = document.createElement("option")
                    option.textContent = data[i].name;
                    option.setAttribute("value", data[i].id);
                    option.dataset.streetAddress = data[i].street_address;
                    option.dataset.latitude = data[i].latitude;
                    option.dataset.longitude = data[i].longitude;
                    locationSelect.append(option)
                }
            })
            .catch(error => {
                console.log(error);
            })
    });
}

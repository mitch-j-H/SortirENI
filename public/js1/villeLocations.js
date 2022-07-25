window.onload = () => {
    // look for locations in city

    let location = document.querySelector("#event_city");



    location.addEventListener("change", function(){
        //select form and row plus value selected
        let form = this.closest("form");
        let data1 = this.name + "=" + this.value;

        console.log(data1);
        console.log(form.getAttribute("method"));

        //jus
        // send using ajax
        fetch(form.action, {
            method: form.getAttribute("method"),
            body: data1,
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
        })
            .then(response => response.text())
            .then(html => {
                // console.log();
                let content = document.createElement("html");
                content.innerHTML = html;
                let nouveauSelect = content.querySelector("#event_city");
                document.querySelector("#event_Location").replaceWith(nouveauSelect);
            })
            .catch(error => {
                console.log(error);
            })
    });
}


//
//AJAX PAR LES DOC

// var $city = $("#event_city");
// // When city gets selected ...
// console.log($city);
// $city.change(function() {
//     // ... retrieve the corresponding form.
//     var $form = $(this).closest('form');
//     // Simulate form data, but only include the selected sport value.
//     var data = {};
//     data[$city.attr('name')] = $city.val();
//     // Submit data via AJAX to the form's action path.
//     $.ajax({
//         url : $form.attr('action'),
//         type: $form.attr('method'),
//         data : data,
//         complete: function(html) {
//             // Replace current position field ...
//             $('#meetup_position').replaceWith(
//                 // ... with the returned one from the AJAX response.
//                 $(html.responseText).find('#event_location')
//             );
//             // Position field now displays the appropriate positions.
//         }
//     });
// });
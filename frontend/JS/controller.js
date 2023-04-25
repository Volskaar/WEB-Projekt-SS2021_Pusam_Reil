//Starting point for JQuery init
$(document).ready(function () {
    loaddata();
});

//on click of every list item show details
$(document).on('click','.list-group-item',function(click){
    showDetail(click.target);
});

//global list of appointments stored for working with data
let appointments = new Array();

function loaddata() {
    //create ajax object with servicehandler.php as url
    //execute querypersonbyname with the input value as a parameter
    $.ajax({
        type: "GET",
        url: "../backend/serviceHandler.php",
        cache: false,
        data: {method: "queryAppointments"},
        dataType: "json",
        async: true,
        //on success create html element list with information on display
        success: function (data) {
            for(let i=0; i<data.length; i++){
                var dataItem = data[i];
            

                for(let j=0; j<dataItem.length; j++){
                    var appointment = dataItem[j];
                    appointments.push(appointment);

                    let a = $("<a href='#' id='"+ i +"' class='list-group-item list-group-item-action border-0 w-100'> </a>");
                    let div = $("<div class='d-flex w-100 justify-content-between'> </div>");
                    let h = $("<h5 class='mb-1'> " + appointment.title + " </h5>");
                    let sm = $("<small>" + appointment.date + "</small>")
                    let p = $("<p class='mb-1'>" + appointment.description + "</p>");
                    let t = $("<small>" + appointment.active + "</small>");

                    div.append(h);
                    div.append(sm);
                    a.append(div);
                    a.append(p);
                    a.append(t);

                    $('#appointmentList').append(a);
                }
            }

            //test
            for(let i=0; i<appointments.length; i++){
                console.log(appointments[i]);
            }
        }
    });
}


function showDetail(target){
    if(target.matches('a')){
        console.log(target.id);

        $('#detailBox').empty();
    
        //basic detail content box
        let card = $("<div class='card w-100 p-3 bg-light'> </div>");
        let h2 = $("<h2> " + appointments[target.id].title + " </h2>");
        let p1 = $("<p> " + appointments[target.id].description + " </p>");
        let p2 = $("<p> " + appointments[target.id].duration + "min </p>");

        //checkbox form
        let form = $("<form> </form>");
        let name = $("<div> <label for='nameInput' class='ms-2'> Name: </label> <input type='text' class='form-control m-2' id='nameInput' placeholder='your name' required> </div>")
        let radioBox = $("<div class='m-2'> </div>");

        for(let i=0; i<appointments[target.id].options.length; i++){
            let radioOption = $("<div class='form-check m-2'> </div>");
            radioOption.append("<input class='form-check-input' type='radio' name='radioOptions' id='radio"+i+"'>");
            radioOption.append("<label class='form-check-label' for='radio"+i+"'> " + appointments[target.id].options[i] + " </label>");
            radioBox.append(radioOption);
        }

        let submit = $("<button class='btn btn-primary m-2' type='submit'>Submit your pick</button>")

        //put together form
        form.append(name);
        form.append(radioBox);
        form.append(submit);

        //put together card
        card.append(h2);
        card.append(p1);
        card.append(p2);
        card.append(form);

        $('#detailBox').append(card);
    }
}
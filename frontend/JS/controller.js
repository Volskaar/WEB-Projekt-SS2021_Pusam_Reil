/* +++ Functionality related to onclick events +++ */

$(document).ready(function () {
    loadAppointmentList();
});

//on click of every list item show details
$(document).on('click','.list-group-item',function(click){
    showDetail(click.target);
});

$(document).on('click','#createAppointment',function(click){
    showNewAppointmentField();
});





/* +++ Functionality related to displaying existing appointments +++ */

function loadAppointmentList() {
    //list of appointments stored for working with data
    let appointments = new Array();
    //create ajax object with servicehandler.php as url
    //execute querypersonbyname with the input value as a parameter
    $.ajax({
        type: "GET",
        url: "../backend/serviceHandler.php",
        cache: false,
        data: {method: "queryAppointments", param: null},
        dataType: "json",
        async: true,
        //on success create html element list with information on display
        success: function (data) {
                for(let j=0; j<data.length; j++){
                    var appointment = data[j];
                    appointments.push(appointment);

                    let a = $("<a href='#' id='"+ appointment[0] +"' class='list-group-item list-group-item-action border-0 w-100'> </a>");
                    let div = $("<div class='d-flex w-100 justify-content-between'> </div>");
                    let h = $("<h5 class='mb-1'> " + appointment[1] + " </h5>"); //title
                    let sm = $("<small>" + appointment[3] + "</small>"); //creation date
                    let p = $("<p class='mb-1'>" + appointment[5] + "</p>"); //descr.
                    let t; //active

                    if(appointment[7]){
                        t = $("<small class='text-success'> active </small>");
                    }
                    else{
                        t = $("<small class='text-danger'> inactive </small>");
                    }

                    div.append(h);
                    div.append(sm);
                    a.append(div);
                    a.append(p);
                    a.append(t);

                    $('#appointmentList').append(a);
                }
        }
    });
}


/* +++ Functionality related to options +++ */

function loadOptionsForAppointment(appID){
    let options = [];

    $.ajax({
        type: "GET",
        url: "../backend/serviceHandler.php",
        cache: false,
        data: {method: "queryOptions", param: appID},
        dataType: "json",
        async: false,
        //on success returns list of text elements containing information about options
        success: function (data) {
            for(let i=0; i<data.length; i++){
                options.push(data[i]);
            }
        }
    });
    
    return options;
}

//call datahandler function to create db entries for options
function createOptions(optionList){
    $.ajax({
        type: "GET",
        url: "../backend/serviceHandler.php",
        cache: false,
        data: {method: "createNewOptions", param: optionList},
        dataType: "json",
        async: false,
        //on success creates options in db
        success: function (id) {
            console.log("options created successfully -> id:" + id);
        },
        error: function(){
            console.log("option creation failed");
        }
    });
}


function submitAppointmentInfo(submittedData){
    //creates a connection between the different inputs in the db
    $.ajax({
        type: "GET",
        url: "../backend/serviceHandler.php",
        cache: false,
        data: {method: "createNewEntry", param: submittedData},
        dataType: "json",
        async: false,

        success: function () {
            console.log("successfully updated");
        }
    });
}

function saveComment(commentEntry){
    //saves the comment and the connection to the user who wrote it
    $.ajax({
        type: "GET",
        url: "../backend/serviceHandler.php",
        cache: false,
        data: {method: "saveNewComment", param: commentEntry},
        dataType: "json",
        async: false,

        success: function () {
            console.log("comment saved");
        }
    });
}

function loadUser(userID, idCount){
    $.ajax({
        type: "GET",
        url: "../backend/serviceHandler.php",
        cache: false,
        data: {method: "queryUser", param: userID},
        dataType: "json",
        async: false,

        success: function (data) {
            console.log("user loaded");
            $("#voting"+idCount).append("<p> " + data[0][1] + "</p>");
        }
    });
}

function loadUserOptions(optionID, idCount){
    $.ajax({
        type: "GET",
        url: "../backend/serviceHandler.php",
        cache: false,
        data: {method: "queryUserOptions", param: optionID},
        dataType: "json",
        async: false,

        success: function (data) {
            console.log("options loaded");
            for(let i = 0; i < data.length; i++){
                $("#voting"+i).append("<p> " + data[i][1] + "</p>");
            }
        }
    });
}

function loadComment(targetID){
    comments = new Array();

    $.ajax({
        type: "GET",
        url: "../backend/serviceHandler.php",
        cache: false,
        data: {method: "queryComment", param: targetID},
        dataType: "json",
        async: false,

        success: function (data) {
            comments = data;
        },
        error: function(){
            console.log("no comments to this appointment");
        }
    });

    return comments;
}

function loadVotings(targetID){
    votings = new Array();

    //query all votings for this appointment
    $.ajax({
        type: "GET",
        url: "../backend/serviceHandler.php",
        cache: false,
        data: {method: "queryVotings", param: targetID},
        dataType: "json",
        async: false,

        success: function (data) {
            votings = data;
        },
        error: function(){
            console.log("no entries to this appointment");
        }
    });

    return votings;
}

function getAppointmentDetails(id){
    //retreives detail information on appointment from db
    details = new Array();
    $.ajax({
        type: "GET",
        url: "../backend/serviceHandler.php",
        cache: false,
        data: {method: "getAppointmentDetails", param: id},
        dataType: "json",
        async: false,
        success: function (data) {
            details = data;
        }
    });

    return details;
}


function showDetail(target){
    if(target.matches('a')){
        targetID = target.id -1;

        //get details from database
        let details = getAppointmentDetails(target.id);

        //display
        $('#detailBox').empty();
    
        //basic detail content box
        let card = $("<div class='card w-100 p-3 bg-light'> </div>");
        let h2 = $("<h2> " + details[0][1] + " </h2>");
        let h5 = $("<h5> Open: " + details[0][3] + " -> " + details[0][4] + "</h5>");
        let p1 = $("<p> " + details[0][5] + " </p>");
        let p2 = $("<p> Duration: " + details[0][6] + "min </p>");

        //checkbox form
        let form = $("<form accept-charset='utf-8'> </form>");
        let name = $("<div> <label for='nameInput' class='ms-2'> Your Name: </label> <input type='text' class='form-control m-2' id='nameInput' placeholder='your name' required> </div>")
        let optionBox = $("<div class='m-2'> </div>");

        let p3 = $("<p class='m-2'> Available Options: </p>");

        //display options
        var options = loadOptionsForAppointment(target.id);
        let table = $("<table class='table'> </table>");
        let thead = $("<thead><tr> </tr></thead>");

        for(let i=0; i<options.length; i++){
            thead.append("<th scope='col'> " + options[i][1] + "</th>");
        }

        let tbody = $("<tbody><tr> </tr></tbody>");

        for(let i=0; i<options.length; i++){
            //sets value from the checkbox to option_id
            tbody.append("<td> <input class='form-check-input' type='checkbox' value='"+ options[i][0] +"' id='flexCheckDefault"+i+"'> </td>");
        }

        table.append(thead);
        table.append(tbody);
        optionBox.append(table);
        optionBox.append(p3);

        //---------------

        let comment = $("<textarea class='form-control' id='comment' placeholder='leave a comment ...'></textarea>");

        let submit = $("<button class='btn btn-primary m-2' id='submitData' type='submit'>Submit your pick</button>");

        $(document).on('click','#submitData',function(click){
            //determines if the specified user is already in the db / If not, he will be added
            let userID = checkForUser($("#nameInput").val());

            //creates an entry in comments"
            let commentEntry = [];
            let text = $("#comment").val();

            commentEntry.push(text);
            commentEntry.push(userID);
            commentEntry.push(targetID);

            //ajax call function
            saveComment(commentEntry);
            
            //~~~~~~~~~~~~~~~~~~~~~


            //creates an entry in "zugriff_options"
            let submittedData = [];
            let optionID = [];

            //saves the id from the checkboxes in optionID[]
            for(i = 0; i < options.length; i++){
                if(document.getElementById("flexCheckDefault"+ i).checked){
                    optionID[i] = parseInt($("#flexCheckDefault"+i).val());
                }
            }

            //Loop to create multiple entries for different options
            for(i = 0; i < optionID.length; i++){
                submittedData.push(target.id);
                submittedData.push(optionID[i]);
                submittedData.push(userID);
    
                //ajax call function
                submitAppointmentInfo(submittedData);
                //Resets submitted Data
                submittedData = new Array;
                //~~~~~~~~~~~~~~~~~~~~~~~~
            }
            
            /*location.reload(true);
            console.log("reload");*/
        });


        //put together form
        form.append(name);
        form.append(p3);
        form.append(optionBox);
        form.append(comment);
        form.append(submit);


        //display previous votes
        prevVotes = $("<div class='container'> <h2> Previous votes </h2> <ul class='list-group list-group-flush'> </ul></div>");

        votes = loadVotings(target.id);

        if(votes.length > 0){
            for(let i=0; i<votes.length; i++){
                prevVotes.append("<li class='list-group-item'> " + votes[i] + "</li>");
            }
        }

        //display previous comments
        prevComments = $("<div class='container'> <h2> Previous Comments </h2> <ul class='list-group list-group-flush'> </ul></div>");

        comments = loadComment(target.id);

        if(comments.length > 0){
            for(let i=0; i<comments.length; i++){
                prevComments.append("<li class='list-group-item'> " + comments[i] + "</li>");
            }
        }

        //put together card
        card.append(h2);
        card.append(h5);
        card.append(p1);
        card.append(p2);
        card.append(form);
        card.append(prevVotes);
        card.append(prevComments);

        //ajax - query votings
        //queryVotings(targetID);

        $('#detailBox').append(card);
    }
}


/* +++ Functionality related to creating a new appointment +++ */

function showNewAppointmentField(){
    $('#detailBox').empty();
    
    //basic detail content box
    let card = $("<div class='card w-100 p-3 bg-light'> </div>");
    let h2 = $("<h2 class='text-center m-2'> Create a new Appointment </h2>")
    let form = $("<form accept-charset='utf-8' method='POST'> </form>");

    //form inputs
    let titleInput = $("<input id='title' class='form-control m-2' type='text' value='Appointment Title' required/>");
    form.append(titleInput);

    let creatorName = $("<input id='creator' class='form-control m-2' type='text' value='Name of creator' required/>");
    form.append(creatorName);

    let placeInput = $("<input id='place' class='form-control m-2' type='text' value='Place of meeting' required/>");
    form.append(placeInput);

    let durationLabel = $("<label for='duration' class='form-label m-2'> Duration: </label>");
    let durationInput = $("<input id='duration' class='form-control m-2' type='number' required/>");
    form.append(durationLabel);
    form.append(durationInput);

    let timeoutLabel = $("<label for='timeout' class='form-label m-2'> Cease Date: </label>");
    let timeoutInput = $("<input id='timeout' class='form-control m-2' type='date' required/>");
    form.append(timeoutLabel);
    form.append(timeoutInput);

    //create more options on demand
    options = [];
    let optionDiv =$("<div class='container'> </div>")
    let optionh4 = $("<h5> Options: </h5>")
    let optionList = $("<ul id='optionList' class='list-group d-flex justify-content-around'> </ul>");
    let newOptionButton = $("<button type='button' class='btn btn-info' id='newOpBtn'> + </button>");

    $(document).on('click','#newOpBtn',function(click){
        $('#optionList').append("<input name='optionBox' class='form-control m-2 appOption' type='datetime-local'/>");
    });

    optionDiv.append(optionh4);
    optionDiv.append(optionList);
    optionDiv.append(newOptionButton);

    form.append(optionDiv);

    let descriptionInput = $("<textarea class='form-control m-2' id='description' placeholder='Describe the meeting ...'></textarea>");
    form.append(descriptionInput);

    let submitNewAppointment = $("<button type='button' class='btn btn-success m-2' id='addAppointment'> Create Appointment </button>");
    
    $(document).on('click','#addAppointment',function(click){
        createNewAppointment();
    });
    
    form.append(submitNewAppointment);

    card.append(h2);
    card.append(form);
    $('#detailBox').append(card);
}


function createNewAppointment(){
    //1. collect data from form and create array to hand over
    let title = $('#title').val();
    let place = $('#place').val();

    //format create_date to fit yyyy-mm-dd
    let today = new Date();
    let yyyy = today.getFullYear();
    let mm = today.getMonth() + 1; // Months start at 0!
    let dd = today.getDate();
    let create_date = yyyy + "-" + mm + "-" + dd;


    let cease_date = $('#timeout').val();
    let description = $('#description').val();
    let duration = $('#duration').val();
    let active = 1;

    //creator must be placed in users if not already there
    //id given to appointmentdb
    let name = $('#creator').val();
    let created_by = checkForUser(name);

    var data = [];
    data.push(title);
    data.push(place);
    data.push(create_date);
    data.push(cease_date);
    data.push(description);
    data.push(duration);
    data.push(active);
    data.push(created_by);

    //get options from list of options into array
    let optionList = [];
    let optionItems = document.getElementsByClassName("appOption");
    for(let i=0; i<optionItems.length; i++){
        optionList[i] = optionItems[i].value;
    }

    //check if all fields are set
    let makeCall = true;
    for(let i=0; i<data.length; i++){
        if(data[i] == null){
            makeCall = false;
        }
    }

    //2. make ajax call to server -> store data
    if(makeCall && optionList.length > 0){
        $.ajax({
            type: "GET",
            url: "../backend/serviceHandler.php",
            cache: false,
            data: {method: "createNewAppointment", param: data},
            dataType: "json",
            async: false,
            success: function () {
                //after creating appointment -> enter options into db with appointment id
                createOptions(optionList);
                
                alert("Successfully created appointment!");
                $('#detailBox').empty();
            }
        });
    }
    else if(optionList.length <= 0){
        alert("You must enter at least one voteable option!");
    }
    else{
        alert("You left a required field open, try again!");
    }
}


/* +++ Functionality related to users +++ */

function checkForUser(username){
    let userid;

    $.ajax({
        type: "GET",
        url: "../backend/serviceHandler.php",
        cache: false,
        data: {method: "checkForUserExistence", param: username},
        dataType: "json",
        async: false,
        //on success return id of user (either existing or newly created)
        success: function (id) {
            userid = id[0][0];
        }
    });

    return userid;
}
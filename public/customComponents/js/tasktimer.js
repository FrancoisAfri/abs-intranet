// var time = 0;
// var running = 0;

function startPause(taskID) {
    if (running[taskID] == 0) {
        //running = 1;
        //call function to fetch the duration from the database and increment the timer
        getTaskDurationAndIncrement(taskID);
        //Show the end task button
        $("button[data-task_id='"+taskID+"']").removeClass('hidden');
        //increment();
        //document.getElementById("startPause").innerHTML = "<i class='fa fa-pause'></i> Pause";
    } else {
        //running = 0;
        //call function to update the duration on the server
        updateTaskDuration(taskID, time[taskID] / 10);
        //document.getElementById("startPause").innerHTML = "<i class='fa fa-repeat'></i> Resume";
    }
}
/*
function reset() {
    running = 0;
    time = 0;
    document.getElementById("startPause").innerHTML = "<i class='fa fa-play'></i> Start";
    //document.getElementById("stopWatchDisplay").innerHTML = "00:00:00";
    document.getElementById("stopWatchDisplay").value = "00:00:00.0";
}
*/
function increment(taskID) {
    if (running[taskID] == 1) {
        setTimeout(function() {
            time[taskID]++;
            var hours = Math.floor(time[taskID] / 10 / 60 / 60) % 60;
            var mins = Math.floor(time[taskID] / 10 / 60) % 60;
            var secs = Math.floor(time[taskID] / 10) % 60;
            var tenths = time[taskID] % 10;

            if (hours < 10) {
                hours = "0" + hours;
            }
            if (mins < 10) {
                mins = "0" + mins;
            }
            if (secs < 10) {
                secs = "0" + secs;
            }
            //document.getElementById("stopWatchDisplay").innerHTML = mins + ":" + secs + ":" + "0" + tenths;
            $('#' + taskID + 'stopWatchDisplay').val(hours + ":" + mins + ":" + secs + "." + tenths);
            increment(taskID);
        }, 100);
    }
}

//function to update the duration on the server
function updateTaskDuration(taskID, timeInSeconds) {
    //console.log('gets here ' + taskID + ' and ' + timeInSeconds);
    $.ajax({
        method: 'GET',
        url: "/api/tasks/" + taskID + "/duration/" + timeInSeconds,
        success: function(success) {
            running[taskID] = 0;
            $('#' + taskID + 'startPause').html("<i class='fa fa-repeat'></i> Resume");
        },
        error: function(xhr) {
            if(xhr.status === 422) {
                var errors = xhr.responseJSON; //get the errors response data

                $.each(errors, function (key, value) {
                    console.log('error thrown: ' + value);
                    //TODO: show the error in a modal
                    alert(value);
                });
            }
        }
    });
}

//function to get the duration from the server and start/increment the timer
function getTaskDurationAndIncrement(taskID) {
    $.ajax({
        method: 'GET',
        url: "/api/tasks/" + taskID + "/get-duration/",
        success: function(data) {
            running[taskID] = 1;
            time[taskID] = data * 10;
            increment(taskID);
            $('#' + taskID + 'startPause').html("<i class='fa fa-pause'></i> Pause");
        },
        error: function(xhr) {
            if(xhr.status === 422) {
                var errors = xhr.responseJSON; //get the errors response data

                $.each(errors, function (key, value) {
                    console.log('error thrown: ' + value);
                    //TODO: show the error in a modal
                    alert(value);
                });
            }
        }
    });
}

//function to end the task timer
function endTask(taskID) {
    if (running[taskID] = 1) { //first update the duration if the task is running
        //running = 0;
        updateTaskDuration(taskID, time[taskID] / 10);
    }

    //upload document, notes and completion date
    var strUrl = '/task/end';
    var formName = 'end-task-form';
    var modalID = 'end-task-modal';
    var submitBtnID = 'end-task';
    var redirectUrl = '/';
    var successMsgTitle = 'Task Ended!';
    var successMsg = 'Task has been Successfully ended!';

    modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
}
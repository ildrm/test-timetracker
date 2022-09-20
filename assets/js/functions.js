/**
 * Show Dashboard Function
 */
function show_dashboard() {
    var req = $.ajax({
        type: 'POST',
        dataType: 'html', // the pages are in HTML, so the request ask for an HTML return 
        data: {
            page: 'dashboard'
        },
        url: '/api.php/api/v1/html', // this route is used to retrieve the static pages
        timeout: 5000,
        beforeSend: function (xhr) {
            xhr.setRequestHeader('Authorization', 'Bearer ' + window.localStorage.getItem('access_token'));
        }
    });
    req.done(function (response) {
        $('#page').html(response);
    });
    req.fail(function (jqXHR, textStatus) {
        if (jqXHR.responseJSON && jqXHR.responseJSON.status == 0) {
            alert(jqXHR.responseJSON.message);
        }
    });
}

/**
 * This function loads the projects
 */
function get_projects() {
    var req = $.ajax({
        type: 'GET',
        dataType: 'json',
        url: '/api.php/api/v1/project', // this route is used to retrieve the projects
        timeout: 5000,
        beforeSend: function (xhr) {
            xhr.setRequestHeader('Authorization', 'Bearer ' + window.localStorage.getItem('access_token'));
        }
    });
    req.done(function (response) {
        let obj = $('#project');
        obj.empty(); // makes the list empty
        $.each(response.data, function (k, v) { // adds the projects one by one
            if (v.id !== undefined) obj.append('<option value="' + v.id + '">' + v.name + '</option>');
        })
    });
    req.fail(function (jqXHR, textStatus) {
        if (jqXHR.responseJSON && jqXHR.responseJSON.status == 0) {
            alert(jqXHR.responseJSON.message);
        }
    });
}

/**
 * This function loads the time records
 */
function get_time_records() {
    var req = $.ajax({
        type: 'GET',
        dataType: 'json',
        url: '/api.php/api/v1/time', // this route is used to retrieve the time records
        timeout: 5000,
        beforeSend: function (xhr) {
            xhr.setRequestHeader('Authorization', 'Bearer ' + window.localStorage.getItem('access_token'));
        }
    });
    req.done(function (response) {
        let obj = $('#records');
        obj.empty(); // clears the block
        $.each(response.data, function (k, v) { // adds the records one by one
            if (v.start_time != undefined && v.end_time != undefined) {
                obj.append('<div>' + v.start_time + ' - ' + v.end_time + '(' + v.duration + ')' + '</div>');
            }
        })
    });
    req.fail(function (jqXHR, textStatus) {
        if (jqXHR.responseJSON && jqXHR.responseJSON.status == 0) {
            alert(jqXHR.responseJSON.message);
        }
    });
}

/**
 * Convert seconds to a readable format [Hours]:[Minutes]:[Seconds]
 * @param {integer} seconds 
 * @returns {string}
 */
function convertSeconds(seconds) {
    var convert = function (x) {
        return (x < 10) ? "0" + x : x;
    }
    return convert(parseInt(seconds / (60 * 60))) + ":" +
        convert(parseInt(seconds / 60 % 60)) + ":" +
        convert(seconds % 60)
}

/**
 * This function starts when you click on the start button
 */
function start_timer() {
    var req = $.ajax({
        type: 'POST',
        dataType: 'json',
        data: {
            "project_id": $('#project option:selected').val()
        },
        url: '/api.php/api/v1/time', // this route adds a new time record
        timeout: 5000,
        beforeSend: function (xhr) {
            xhr.setRequestHeader('Authorization', 'Bearer ' + window.localStorage.getItem('access_token'));
        }
    });
    req.done(function (response) {
        sec = 0;
        $('#clock').html('00:00:00');
        $('#start_timer').prop('disabled', true); // makes the start button disabled
        $('#stop_timer').prop('disabled', false); // makes the stop button enabled
        obj.Start(); // makes the timer started
        activeServerTimeId = response.data.id; // sets the time record id
    });
    req.fail(function (jqXHR, textStatus) {
        if (jqXHR.responseJSON && jqXHR.responseJSON.status == 0) {
            alert(jqXHR.responseJSON.message);
        }
    });
}

/**
 * This function starts when you click on the stop button
 */
function stop_timer() {
    var req = $.ajax({
        type: 'PUT',
        dataType: 'json',
        data: {},
        url: '/api.php/api/v1/time/' + activeServerTimeId, // this route eddits the existed record
        timeout: 5000,
        beforeSend: function (xhr) {
            xhr.setRequestHeader('Authorization', 'Bearer ' + window.localStorage.getItem('access_token'));
        }
    });
    req.done(function (response) {
        $('#stop_timer').prop('disabled', true); // makes the stop button disabled
        $('#start_timer').prop('disabled', false); // makes the start button enabled
        obj.Stop(); // makes the timer stoped
        activeServerTimeId = null; // removes the time record id
        get_time_records(); // reloads the records
    });
    req.fail(function (jqXHR, textStatus) {
        if (jqXHR.responseJSON && jqXHR.responseJSON.status == 0) {
            alert(jqXHR.responseJSON.message);
        }
    });
}

function logout() {
    window.localStorage.removeItem('access_token');
    location.reload();
}
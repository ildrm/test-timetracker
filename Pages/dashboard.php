<div class="dashboard">
    <div id="menus">

    </div>
    <div id="actions">
        <button onclick="logout()">Logout</button>
        <div id="clock">00:00:00</div>
        <button id="start_timer" onclick="start_timer();">Start</button> 
        <button id="stop_timer" onClick="stop_timer();" disabled>Stop</button>
        <select name="project" id="project"></select>
    </div>
    <div id="records"></div>
</div>

<script>
    $(document).ready(function(){
        get_projects();         // loads the projects
        get_time_records();     // loads the time records
    });
</script>
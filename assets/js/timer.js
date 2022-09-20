var Timer = function()
{
    this.Interval = 1000;               // time interval
    this.Enable = new Boolean(false);   // flag to enable/disable
    this.Tick;                          // times runs this callback function with the time interval

    var timerId = 0;                    // interval id of the timer
    var thisObject;                     // instance of this class
    
    // Start function
    this.Start = function()
    {
        this.Enable = new Boolean(true);
 
        thisObject = this;
        if (thisObject.Enable)
        {
            thisObject.timerId = setInterval(
            function()
            {
            	thisObject.Tick(); 
            }, thisObject.Interval);
        }
    };
    
    // Stop function
    this.Stop = function()
    {            
        thisObject.Enable = new Boolean(false);
        clearInterval(thisObject.timerId);
    }; 
};

var sec=0;                      // this holds seconds
var activeServerTimeId=null;    // this holds the time record id (from server)
var obj = new Timer();          // create the timer
obj.Interval = 1000;            // setting the interval
obj.Tick = function() {         // defining the Tick callback function
	sec++;  // increment the seconds
	$('#clock').html(convertSeconds(sec));  // shows the time has passed 
};
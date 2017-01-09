Cron = {

	// the jobs
	// - id: INT
	// - type: 
	//   - loop (repeat every timer seconds)
	//   - once (run with a chance of 1 to timer)
	// - timer: INT
	// - message: the event message
	jobs : [
			'{"id":"1", "type":"loop", "timer":"10", "message":"sync"}',
			'{"id":"2", "type":"once", "timer":"1", "message":"fetch-availability"}',
			'{"id":"3", "type":"once", "timer":"1", "message":"fetch-personalia"}',
			'{"id":"4", "type":"once", "timer":"1", "message":"fetch-share"}'
	],
	
	// reference to the current timers to be able to stop the 'loop' jobs
	timers : [],

	// main cron actions, start & stop
	Message : function(e) {
		var data = JSON.parse(e.data);
		switch (data.message) {
			case "start":
				Cron.Start();
				break;
			case "stop":
				Cron.Stop();
				break;
		}
	},
	
	// start creates a new one time job or a loop for each job
	Start : function() {
		for (var i=0;i<Cron.jobs.length;i++) {
			var job = JSON.parse(Cron.jobs[i]);
			if (job.type == "loop") {
				Cron.Loop(job.id, job.timer, job.message);
			}
			if (job.type == "once") {
				Cron.Once(job.timer, job.message);
			}
		}
	},
	
	// stop clears the current loops
	Stop : function() {
		for (var i=0;i<Cron.timers.length;i++) {
			clearTimeout(Cron.timers[i]);
		}
	},
	
	// repeat message 'message' after 'timer' time 
	Loop : function(id, timer, message) {
		postMessage('{"message":"'+ message + '"}');
		var repeat = Math.floor(timer * 1000);
		Cron.timers[id] = setTimeout(function() { Cron.Loop(id,timer,message); }, repeat);
	},

	// display message 'message' with a change of 1 to 'timer'  
	Once : function(timer, message) {
		if (  Math.floor((Math.random() * timer) + 1) == 1 ) {
			postMessage('{"message":"'+ message + '"}');
		}
	}
	
}

addEventListener("message", Cron.Message, false);

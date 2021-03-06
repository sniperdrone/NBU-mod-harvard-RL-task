<!doctype html>
<html>
<head>
	<title>NBU modded Harvard HIT</title>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
	<script src="jspsych/jspsych.js"></script>
	<script src="jspsych/plugins/jspsych-text.js"></script>
	<script src="jspsych/plugins/jspsych-survey-likert.js"></script>
	<script src="jspsych/plugins/jspsych-space-novel-stim.js"></script>
	<script src="jspsych/plugins/jspsych-space-novel-alien-stim.js"></script>
	<script src="jspsych/plugins/jspsych-space-novel-rocket-stim.js"></script>
	<script src="jspsych/plugins/jspsych-survey-text.js"></script>
	<script src="jspsych/plugins/jspsych-html.js"></script>
	<script src="jspsych/plugins/jspsych-instructions.js"></script>
	<script src="jspsych/plugins/jspsych-call-function.js"></script>
	<script src="additional-functions.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
	<link href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/themes/black-tie/jquery-ui.min.css" rel="stylesheet" type="text/css"></link>
	<link href="jspsych/css/jspsych.css" rel="stylesheet" type="text/css"></link>
</head>
<body>
</body>
<script>


// set up variables as
var max = 5;
var min = -4;
var sd = 2;

if (Math.random()<0.5){
	var p_rews = [Math.ceil(Math.random() * max), Math.floor(Math.random() * min)];	
} else {
	var p_rews = [Math.floor(Math.random() * min), Math.ceil(Math.random() * max)];	
}

if (Math.random()<0.5){
	var rews = [Math.ceil(Math.random() * max), Math.floor(Math.random() * min)];	
} else {
	var rews = [Math.floor(Math.random() * min), Math.ceil(Math.random() * max)];	
}

var gaussian = [];
for (i = 0; i < 1000; i++) {
	gaussian[i] = createMemberInNormalDistribution(0,sd);
}

var nrpracticetrials = 25;
// var nrtrials = 125;
var nrtrials = 3;
var nrrockettrials = 3;

var p_alien_1_rews = [4, 5, 3, 1, -1];
var p_alien_2_rews = [-5, -5, -2, 1, -1];

var sex = '';
var age = 0;
var score = 0;

var subid = '';

var show_reminder = false;

/* jspsych blocks */

var change_colors = {
	type: 'call-function',
	func: function(){ 
		$('.jspsych-display-element').css('background-color', 'black');
		$('.jspsych-display-element').css('color', 'white');
	}
}

var welcome_block = {
	type: "text",
	text: "<div class='center-content'><br><br><br><br>Welcome to this HIT. Today, you will play a  game in which you fly spaceships to different planets to get space treasure.<br><br>Press any key to continue",
};

var id_question = ["Worker ID:"];
var id_block = {
	type: 'survey-text',
	questions: [id_question],
	preamble: ["<div align=center>Please enter your Amazon Mechanical Turk Worker ID below.<br><br>If you do not enter it accurately, we will not be able to pay you.</div>"],
};
var check_id_block = { 
	chunk_type: 'while', 
	timeline: [id_block], 
	continue_function: function(data){ 
		chunk_type: 'while', 
		answers = data[0].responses.split(":"); 
		id = answers[1].split('"')[1];
		console.log("GOTCHA BRO, this is ID: " + id);
		if (id){ 
			subid = id;
			id_trial = new Array(1);
			id_trial[0] = {
				// subid: subid
				subid: subid
			}
			console.log("IN THE ID LOOP, HERES SUBID: " + subid);
			// save_data(id_trial,"space_novel_subid");
			return false; 
		} else {
			alert("Please provide your Amazon Mechanical Turk Worker ID."); 
			return true; 
		}
	},
	timing_post_trial: 0,
};

var check_consent = function(elem) {
	if ($('#consent_checkbox').is(':checked')) {
		return true;
	}
	else {
		alert("If you wish to participate, you must check the box next to the statement 'I agree to participate in this study.'");
		return false;
	}
	return false;
};
var consent_block = {
	type:'html',
	pages: [{url: "consent.html", cont_btn: "start", check_fn: check_consent}],
	on_finish: function(data){
		start_instructions = data.time_elapsed;
	}
};

var instructions_1a_block = {
	type: "instructions",
	pages: instructions_1a_text(),
	key_forward: "j",
	key_backward: "f",
	show_clickable_nav: true,
}
var instructions_1b_block = {
	type: "instructions",
	pages: instructions_1b_text(),
	key_forward: "j",
	key_backward: "f",
	show_clickable_nav: true,
}
var instructions_1c_block = {
	type: "instructions",
	pages: instructions_1c_text(),
	key_forward: "j",
	key_backward: "f",
	show_clickable_nav: true,
}
var instructions_1d_block = {
	type: "instructions",
	pages: instructions_1d_text(),
	key_forward: "j",
	key_backward: "f",
	show_clickable_nav: true,
}
var instructions_1e_block = {
	type: "instructions",
	pages: instructions_1e_text(),
	key_forward: "j",
	key_backward: "f",
	show_clickable_nav: true,
}

var alien_1_practice_block = {
	type: "space-novel-alien-stim",
	choices: "space",
	rews: function() {
		return p_alien_1_rews.splice(0,1)
	},
	timing_post_trial: 0,
	nrtrials: 5,
};
var alien_2_practice_block = {
	type: "space-novel-alien-stim",
	choices: "space",
	rews: function() {
		return p_alien_2_rews.splice(0,1)
	},
	timing_post_trial: 0,
	nrtrials: 5,
	state_name: "yellow",
};

var rocket_practice_block = {
	type: "space-novel-rocket-stim",
	choices: ["F","J"],
	timing_post_trial: 0,
	nrtrials: nrrockettrials,
};

var reminder_1_block = {
	type: "text",
	text: "<div class='center-content'><br><br>Just as a reminder, pick the spaceships that get you to the green state.<br><br><img style='margin:0px auto;display:block;height:200px' src='img/green_planet.png'/><br><br>Press any key to begin.",
};
var reminder_1_if = {
	chunk_type: 'if',
	timeline: [reminder_1_block],
	conditional_function: function(){
		return show_reminder;
	}
};
var rocket_1_while_block = {
    chunk_type: 'while',
    timeline: [reminder_1_if, rocket_practice_block],
    continue_function: function(data){
		var lastchunkdata = jsPsych.data.getTrialsOfType("space-novel-rocket-stim");
		var rocket_score = 0;
		for (j=0; j < nrrockettrials; j++){
			if (lastchunkdata[lastchunkdata.length-1-j].state2 == 1){
				rocket_score = rocket_score + 1;
			}
		}
		if (rocket_score/nrrockettrials == 1){
			show_reminder = false;
			return false;
		} else {
			show_reminder = true;
			return true;
		}
    }
};

var reminder_2_block = {
	type: "text",
	text: "<div class='center-content'><br><br>Just as a reminder, pick the spaceships that get you to the yellow state.<br><br><img style='margin:0px auto;display:block;height:200px' src='img/yellow_planet.png'/><br><br>Press any key to begin.",
};
var reminder_2_if = {
	chunk_type: 'if',
	timeline: [reminder_2_block],
	conditional_function: function(){
		return show_reminder;
	}
};
var rocket_2_while_block = {
    chunk_type: 'while',
    timeline: [reminder_2_if, rocket_practice_block],
    continue_function: function(data){
		var lastchunkdata = jsPsych.data.getTrialsOfType("space-novel-rocket-stim");
		var rocket_score = 0;
		for (j=0; j < nrrockettrials; j++){
			if (lastchunkdata[lastchunkdata.length-1-j].state2 == 2){
				rocket_score = rocket_score + 1;
			}
		}
		if (rocket_score/nrrockettrials == 1){
			show_reminder = false;
			return false;
		} else {
			show_reminder = true;
			return true;
		}
    }
};

var space_practice_block = {
	type: "space-novel-stim",
	rews: function() { 
		alldata = jsPsych.data.getTrialsOfType('space-novel-stim');
		if (alldata.length==0) {
			return p_rews
		} else {
		//drifting probabilities
			for (j = 0; j < 2; j++) {
				g = Math.round(gaussian[Math.floor(Math.random()*gaussian.length)]);
				p_rews[j] = p_rews[j]+g;
				p_rews[j] = Math.min(p_rews[j],Math.max(max*2 - p_rews[j], min));
				p_rews[j] = Math.max(p_rews[j], Math.min(min*2 - p_rews[j], max));
			}
			return p_rews
		}
	},
	timing_post_trial: 0,
	practice: 1,
	timing_response: -1,
	nrtrials: nrpracticetrials,
	subid: function(){
		return subid
	},
};

var instructions_2_block = {
	type: "instructions",
	pages: instructions_2_text(),
	key_forward: "j",
	key_backward: "f",
	show_clickable_nav: true,
	on_finish: function(data){
		//totalreadingtime = data.time_elapsed - start_instructions;
	}
}

var space_block = {
	type: "space-novel-stim",
	rews: function() {
		alldata = jsPsych.data.getTrialsOfType('space-novel-stim');
		if (alldata.length==nrpracticetrials) {
			return rews
			console.log("in first if block" + rews + " \n rews details: " + rews[0] + " length " + rews.length);
		} else {
			//drifting probabilities
			for (j = 0; j < 2; j++) {
				g = Math.round(gaussian[Math.floor(Math.random()*gaussian.length)]);
				rews[j] = rews[j]+g;
				rews[j] = Math.min(rews[j],Math.max(max*2 - rews[j], min));
				rews[j] = Math.max(rews[j], Math.min(min*2 - rews[j], max));


			}
			console.log("in else block" + rews + " \n rews details: " + rews[0] + " length " + rews.length);
			return rews
		}
	},
	timing_post_trial: 0,
	timing_response: 2000,
	nrtrials: nrtrials,
	subid: function(){
		return subid
	},
};

var save_data_block = {
	type: 'call-function',
	func: function(){
		// save_data(jsPsych.data.getTrialsOfType("space-novel-stim"),"space_novel_data");
	}
}

var demographics_block = {
	type: "survey-text", 
	preamble: ["Please provide us with some information about yourself:"], 
	questions: [["Age", "Sex (m/f)"]],
};
var check_demographics_block = { 
	chunk_type: 'while', 
	timeline: [demographics_block], 
	continue_function: function(data){ 
		answers = data[0].responses.split(":"); 
		age_ans = answers[1].split('"')[1]; 
		sex_ans = answers[2].split('"')[1];
		totaltime = data[0].time_elapsed;
		if (jQuery.isNumeric(age_ans) && (sex_ans == 'm' || sex_ans == 'f')){ 
			age = parseInt(age_ans);
			sex = sex_ans;
			// compute score
			alldawtrials = jsPsych.data.getTrialsOfType("space-novel-stim");
			score = alldawtrials[alldawtrials.length-1].score;
			return false; 
		} else { 
			if (!jQuery.isNumeric(age)) 
				alert("Please enter your age as a number (make sure to remove any spaces)."); 
			if (sex != 'm' && sex != 'f') 
				alert("You entered your sex incorrectly. Please write \"f\" if you are female and \"m\" if you are male."); 
			return true; 
		}
	}
}

var save_subinfo_block = {
	type: 'call-function',
	func: function(){
		var lasttrialdata = jsPsych.data.getLastTrialData();
		subinfo = new Array(1);
		subinfo[0] = {
			subid: subid,
			age: age,
			sex: sex,
			score: score,
			time_elapsed: lasttrialdata.time_elapsed,
		};
		// save_data(subinfo,"space_novel_subinfo");
	}
}

var debriefing_block = {
	type:'html',
	pages: [{url: "debriefing.html", cont_btn: "continue"}]
};

var score_block = {
	type: 'text',
	text: function(){
		scoreinnovelar = score/100/2;
		if (scoreinnovelar > 0){
			textscore = scoreinnovelar.toFixed(2);		
			var text = "<br><br><br><br>You won an additional $" + textscore + " on top of your regular payment for this HIT.<br><br>We will process this as soon as possible.<br><br>Press any key to continue";
		} else {
			var text = "<br><br><br><br>You did not win additional payment during the experiment.<br><br>Press any key to continue";
		}
		return text
	},
	on_finish: function(data){
         jsPsych.data.localSave("Rocket_Task_Local_Save" + subid +".csv", 'csv');
   }
};

var end_block = {
	type:'html',
	pages: [{url: "end.html"}]
};

/* create experiment definition array */
var experiment = [];

experiment.push(change_colors);
experiment.push(check_id_block);
/*
experiment.push(consent_block);
experiment.push(welcome_block);
experiment.push(instructions_1a_block);
experiment.push(alien_1_practice_block);
experiment.push(instructions_1b_block);
experiment.push(alien_2_practice_block);
experiment.push(instructions_1c_block);
experiment.push(rocket_practice_block);
experiment.push(rocket_1_while_block);
experiment.push(instructions_1d_block);
experiment.push(rocket_practice_block);
experiment.push(rocket_2_while_block);
experiment.push(instructions_1e_block);
experiment.push(space_practice_block);

experiment.push(instructions_2_block);
*/
experiment.push(space_block);
//experiment.push(save_data_block);
experiment.push(check_demographics_block);
//experiment.push(save_subinfo_block);
//experiment.push(debriefing_block);
experiment.push(score_block);

experiment.push(end_block);

jsPsych.preloadImages(images, function(){ startExperiment(); });

/* start the experiment */
function startExperiment(){
	jsPsych.init({
		experiment_structure: experiment
});
}
</script>
</html>

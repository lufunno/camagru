

/* prop object */

var Prop = function(prop_name) {
	this.name = prop_name;
	this.src = 'img/props/' + prop_name + '.png';
	this.id = 'prop_' + prop_name;
	// this.clickable_element = document.getElementById(prop_name);
	// this.class = this.clickable_element.getAttribute('class');
	this.selected = false;
	if (this.name == 'moustache')
		this.position = {x:45, y:45};
}

Prop.prototype.clear = function() {
	var video = document.getElementById('image_div');

	video.removeChild(document.getElementById(this.id));
	this.selected = false;
}

Prop.prototype.add = function() {
	var video = document.getElementById('image_div');
	var new_prop = document.createElement("img");

	this.selected = true;
	new_prop.setAttribute('src', this.src);
	new_prop.setAttribute('id', this.id);
	new_prop.setAttribute('class', 'superimposed_prop');

	// set size + position
	video.appendChild(new_prop);
}

Prop.prototype.select_min_prop = function() {
	// var new_prop = document.createElement("input");

	this.clickable_element = new_prop;
	// new_prop.setAttribute('src', this.src);
	// new_prop.setAttribute('id', this.name);
	// new_prop.setAttribute('class', 'prop');
	// new_prop.setAttribute('type', 'image');

	// prop_div.appendChild(new_prop);
}

/******************************************************************/

function selected_props() {
	var selected_props;

	for (i = 0; props_obj[i]; i++)
	{
		if (props_obj[i].selected == true)
		{
			if (selected_props)
				selected_props = selected_props + ';' + props_obj[i].src;
			else
				selected_props = props_obj[i].src + ':' + props_obj[i].position;
		}
	}
	return (selected_props);
}

function listen(prop) {
	prop.clickable_element.addEventListener('click', function(ev){
		ev.preventDefault;
		if (!document.getElementById(prop.id))
			prop.add();
	}, false);
}

function disable_all_props(props_obj) {
	for (i = 0; props_obj[i]; i++) {
		props_obj[i].clickable_element.setAttribute('disabled', 'true');
	}
}

function enable_all_props(props_obj) {
	for (i = 0; props_obj[i]; i++) {
		props_obj[i].clickable_element.removeAttribute('disabled');
	}
}

function clear_all_props() {
	for (i = 0; props_obj[i]; i++)
	{
		if (props_obj[i].selected == true)
			props_obj[i].clear();
	}
}

var prop_div = (function(){ return(document.getElementById('prop_div'));
})();

var props = (function(){
	return (['batman', 'moustache', 'red_nose', 'trump_hair', 'flower_frame', 'beard', 'rainbow', 'xmas', 'xmas_hat', 'glasses', 'sunglasses', 'giraffe', 'beer', 'wolf', 'crown']);
}());

var props_obj = (function (){
	var props_obj = {};

// add elements in php instead, + opti

	for (i = 0; props[i]; i++)
	{
		props_obj[i] = new Prop(props[i]);
		props_obj[i].clickable_element = document.getElementById(props_obj[i].name);
		console.log(props_obj[i]);
		// props_obj[i].add_min_prop();
		listen(props_obj[i]);
	}
	return props_obj;
	})();
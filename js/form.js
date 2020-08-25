(function(){

var password = document.getElementById('new_password'),
	conf_password = document.getElementById('conf_password'),
	reinit_password = document.getElementById('reinit_password');

conf_password.onchange = function () {
	if (conf_password.value !== password.value)
		conf_password.style.border = '2px solid red';
}

})();
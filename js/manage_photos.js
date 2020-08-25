(function(){

var delete_list = document.getElementsByClassName('delete_photo');

for (i = 0; delete_list[i]; i++) {
	delete_list[i].addEventListener('click', function(ev) {

		// alert: are you sure?
		var photo_name = ev.target.id.replace("delete_photo_", "");
		var photo_div = document.getElementById("gallery_section_" + photo_name);
		var request = new XMLHttpRequest();

		request.open("POST", "delete_photo.php");
    	request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded', true);
		request.send("photo=" + photo_name);
		request.onreadystatechange = function(){
			 if (request.readyState == 4 && request.status == 200) {
			 	// message success
				photo_div.parentNode.removeChild(photo_div);
				// + add other photo! -> in delete_photo.php get next photo from page (send page nb, )
			 	console.log(request.responseText);
          }};

	}, false);
}

})();
(function(){


var gallery = document.getElementsByClassName('gallery_img');

if (gallery.length)
{
  var list_like_but = document.getElementsByClassName('like');
var list_show_but = document.getElementsByClassName('show_comments');
var list_hide_but = document.getElementsByClassName('hide_comments');
var section_height = document.getElementsByClassName('gallery_img')[0].offsetHeight;

for(i = 0; list_like_but[i]; i++) {
		list_like_but[i].addEventListener('click', function(ev) {
			var like = document.getElementById(ev.target.id);
			var nb_likes = document.getElementById(ev.target.id.replace("like_", "nb_likes_"));
    		var id  = like.getAttribute('id').replace("like_", "");
			var request = new XMLHttpRequest();

			if (like.style.background == 'black')
			{
				like.style.background = '#e6e6e6';
				request.open("POST", "unlike.php");
				request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded', true);
    			request.send(encodeURI('name=' + id));
    			request.onreadystatechange = function (){
          		if (request.readyState == 4 && request.status == 200)
          		{
          			console.log("response: " + request.responseText);

          			var txt = document.createTextNode(request.responseText);
          			console.log("txt:" + txt.textContent);
          			nb_likes.innerHTML = txt.textContent;
          			console.log("unlike");
             	}

          		};
			}
			else
			{
				like.style.background = "black";
				request.open("POST", "like.php");
				request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded', true);
    			request.send(encodeURI('name=' + id));
    			request.onreadystatechange = function (){
          		if (request.readyState == 4 && request.status == 200)
          		{
          			var txt = document.createTextNode(request.responseText);
          			console.log("txt:" + txt);
          			nb_likes.innerHTML = txt.textContent;
          			console.log("like");
             		console.log(request.responseText);
             	}

          }};
		}, false);
}

for (i = 0; list_show_but[i]; i++) {
		list_show_but[i].addEventListener('click', function(ev) {
  		var show_button = document.getElementById(ev.target.id);

      if(show_button.innerHTML.search('0 comments') == -1)
      {
        // for(i=0;show_button.innerHTML[i];i++)
          // console.log(i + ': ' + show_button.innerHTML[i]+ ',');
        var comments = document.getElementById(ev.target.id.replace('show_comments_', "comments_"));
        var hide_button = document.getElementById(ev.target.id.replace("show_comments_", "hide_comments_"));
        var comment_text = comments.childNodes;

        for (i = 0; comment_text[i]; i++) {
            // console.log(comment_text[i]);
            comment_text[i].style.width = document.getElementsByClassName('gallery_img')[0].offsetWidth.toString();
        }
        show_button.style.display = 'none';
        hide_button.style.display = 'initial';
		  	comments.style.display = 'block';
        comments.style.height = 'calc(' + section_height.toString() + 'px - 20px)';
        comments.style.width = '95%';
      }
		}, false);
}

for (i = 0; list_hide_but[i]; i++) {
    list_hide_but[i].addEventListener('click', function(ev) {
      var hide_button = document.getElementById(ev.target.id);
      var comments = document.getElementById(ev.target.id.replace('hide_comments_', "comments_"));
      var show_button = document.getElementById(ev.target.id.replace("hide_comments_", "show_comments_"));


      show_button.style.display = 'initial';
      hide_button.style.display = 'none';
      comments.style.display = 'none';
    },false);
  }
}
})();


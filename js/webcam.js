
(function(){

var  errorElement = document.querySelector('errorMsg'),
	   video = document.querySelector('video'),
     video_div = document.getElementById('video_div'),
	   saved_photo = document.querySelector('#saved_photo'),
	   canvas = document.querySelector('canvas'),
     uploaded_pic,

	   startbutton = document.getElementById('startbutton'),
     savebutton = document.getElementById('savebutton'),
     uploadbutton = document.getElementById('upload_pic'),
     clearbutton = document.getElementById('clearbutton'),
     backbutton = document.getElementById('backbutton'),
     tocambutton = document.getElementById('tocambutton'),

     streaming = false,
     width = 620,//document.getElementById('video_div').style.width,
     height = 0,
	   constraints = window.constraints = {audio: false, video: {
      mandatory: {minWidth: 320, maxWidth: width}}},
      track,
      file,
      data;

var promisifiedOldGUM = function(constraints) {

  // First get ahold of getUserMedia, if present
  var getUserMedia = (navigator.getUserMedia ||
      navigator.webkitGetUserMedia ||
      navigator.mozGetUserMedia ||
      navigator.msGetUserMedia);

  // Some browsers just don't implement it - return a rejected promise with an error
  // to keep a consistent interface
  if(!getUserMedia) {
    return Promise.reject(new Error('getUserMedia is not implemented in this browser'));
  }

  // Otherwise, wrap the call to the old navigator.getUserMedia with a Promise
  return new Promise(function(resolve, reject) {
    getUserMedia.call(navigator, constraints, resolve, reject);
  });

}

// Older browsers might not implement mediaDevices at all, so we set an empty object first
if(navigator.mediaDevices === undefined) {
  navigator.mediaDevices = {};
}

if(navigator.mediaDevices.getUserMedia === undefined) {
  navigator.mediaDevices.getUserMedia = promisifiedOldGUM;
}


navigator.mediaDevices.getUserMedia(constraints)
	.then(function (stream){
	video.srcObject = stream;//window.URL.createOnjectURL(stream); // handle all browsers?
  track = stream.getVideoTracks()[0];
  video.play();
  height = video.videoHeight / (video.videoWidth/width);

	// video.onloadmetadata = function(e) { video.play(); };
	})
	.catch(function(err) {
    console.log("An error occured!" + err.name);});

video.addEventListener('canplay', function(ev) {
	if(!streaming) {
		height = video.videoHeight / (video.videoWidth/width);
  console.log(video.videoWidth);

    video.setAttribute('width', width);
		video.setAttribute('height', height);
		canvas.setAttribute('width', width);
		canvas.setAttribute('height', height);
		saved_photo.setAttribute('width', width);
		saved_photo.setAttribute('height', height);
    photo_div.setAttribute('width', width + 'px');
    photo_div.setAttribute('height', height);
		streaming = true;
	}
}, false);

 function takepicture() {
     take_request = new XMLHttpRequest();
    var uploaded_pic = document.getElementById('uploaded_pic');

  take_request.open("POST", "take_photo.php");
    take_request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded', true);//saved_photo.getAttribute('src'));

     console.log("SELECTED PROPS: " + selected_props());

    if (!uploaded_pic)
    {
      canvas.width = width;
      canvas.height = height;
      canvas.getContext('2d').drawImage(video, 0, 0, width, height);
      data = canvas.toDataURL('drawImage/png');
      saved_photo.setAttribute('src', data);
      saved_photo.style.zindex = '0'
      data = data.replace("data:image/png;base64,", "");
      take_request.send(encodeURI('image=' + data + "&props=" + selected_props()));
    }
    else
    {
          var reader = new FileReader();
          reader.addEventListener("loadend", function() {
          // reader.result contains the contents of blob as data
            data = reader.result.replace("data:image/png;base64,", "");
            data = data.replace("data:image/jpeg;base64,", "");
            // console.log(data);
            take_request.send(encodeURI('image=' + data + "&props=" + selected_props()));
          });
          reader.readAsDataURL(file);
    }


    take_request.onreadystatechange = function (){
          if (take_request.readyState == 4 && take_request.status == 200)
          {
              disable_all_props(props_obj);
              console.log("RESPONSE TEXT: " + take_request.responseText);
          }
          // clear_all_props();
      };
}

  function save_picture() {
     save_request = new XMLHttpRequest();
    save_request.open("POST", "save_photo.php");
    save_request.send();
    save_request.onreadystatechange = function (){

          if (save_request.readyState == 4 && save_request.status == 200)
          {
             //add miniature element in DOM    ---- if limit already reached replace one of the mins
            var lib = document.getElementById('min_photo_library');
            var new_photo = document.createElement('img');
            var children = lib.childNodes;
            var more = document.getElementById('more');

            new_photo.setAttribute('src', save_request.responseText);
            new_photo.setAttribute('class', 'min_img col-lg-4 col-md-4 col-sm-4');
            if (children.length >= 20)
            {
              lib.removeChild(children[1]);   //children[Math.floor(Math.random() * (18 - 1)) + 1]);
              console.log(more);
              if (more == null)
              {
                more = document.createElement('a');
                more.setAttribute('href', 'account.php?act=pht');
                more.setAttribute('class', 'col-lg-12 col-md-12 col-sm-12');
                more_txt = document.createTextNode('...more');
                more.appendChild(more_txt);
                lib.parentNode.appendChild(more);
              }
            }
            lib.appendChild(new_photo);
            enable_all_props(props_obj);
            clear_all_props();
          }};
  }

  startbutton.addEventListener('click', function(ev){
    ev.preventDefault();
    if (typeof(selected_props()) != 'undefined')
     {
        takepicture();
       video_div.style.display = 'none';
        track.enabled = false;
       photo_div.style.display = 'initial';
      startbutton.style.display = 'none';
      uploadbutton.style.display = 'none';
      savebutton.style.display = 'initial';
      backbutton.style.display = 'initial';
      clearbutton.style.display = 'none';




  }
  }, false);

savebutton.addEventListener('click', function(ev){
      save_picture();
      clear_all_props();
      track.enabled = true;
      video_div.style.display = 'block';
      photo_div.style.display = 'none';
      savebutton.style.display = 'none';
      startbutton.style.display = 'initial';
       backbutton.style.display = 'none';
       clearbutton.style.display = 'initial';
    ev.preventDefault();
  }, false);

// function check_extension(name){
//   if (name.search(/.*\.jpe{0,1}g/i) != -1 || name.search(/.*\.png/i) != -1)
//     return true;
//   return false;
// }

uploadbutton.addEventListener('change', function(ev) {

    if ('files' in uploadbutton) {
      if (uploadbutton.files.length == 1 && (uploadbutton.files[0].type == 'image/png' || uploadbutton.files[0].type == 'image/jpeg'))
      {
         var uploaded_pic = document.getElementById('uploaded_pic');

         tocambutton.style.display = 'initial';
        if (uploaded_pic && typeof(uploaded_pic) != 'undefined')
            photo_div.removeChild(uploaded_pic);
        file = uploadbutton.files[0];
        var file_url = (window.URL || window.webkitURL).createObjectURL(file);
        uploaded_pic = document.createElement("img");
        uploaded_pic.setAttribute('src', file_url);
        uploaded_pic.setAttribute('id', 'uploaded_pic');
        uploaded_pic.setAttribute('width', width.toString());
        uploaded_pic.setAttribute('height', height.toString());
        video_div.style.display = 'none';
        saved_photo.style.display = 'none';
        track.enabled = false;
        photo_div.style.display = 'initial';
        photo_div.appendChild(uploaded_pic);
        startbutton.style.display = 'initial';
      }
    }
}, false);

tocambutton.addEventListener('click', function(ev){
        video_div.style.display = 'block';
        tocambutton.style.display = 'none';
        uploaded_pic = document.getElementById('uploaded_pic');
        console.log(uploaded_pic);
        if (uploaded_pic && typeof(uploaded_pic) != 'undefined')
              photo_div.removeChild(uploaded_pic);
        track.enabled = true;
        uploadbutton.style.marginleft = '10%';
},false);

clearbutton.addEventListener('click', function(ev) {
      clear_all_props();
}, false);

backbutton.addEventListener('click', function(ev) {
      backbutton.style.display = 'none';
      savebutton.style.display = 'none';
      photo_div.style.display = 'none';
      clearbutton.style.display = 'initial';
      video_div.style.display = 'block';
      startbutton.style.display = 'initial';
      uploadbutton.style.display = 'initial';
      track.enabled = true;
      var uploaded_pic = document.getElementById('uploaded_pic');
      if (uploaded_pic && uploaded_pic != 'undefined')
        photo_div.removeChild(uploaded_pic);
      clear_all_props();
}, false);


})();

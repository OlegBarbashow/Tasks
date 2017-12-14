$(document).ready(function(){
	
  function handleFileSelect(evt) {
    var files = evt.target.files;

    for (var i = 0, f; f = files[i]; i++) {

      if (!f.type.match('image.*')) {
        continue;
      }

      var reader = new FileReader();

      reader.onload = (function(theFile) {
        return function(e) {
          
          var span = document.createElement('span');
          span.innerHTML = ['<img style="height:240px; widtg:320px" class="img-responsive" src="', e.target.result,
                            '" title="', theFile.name, '"/>'].join('');
          document.getElementById('list').insertBefore(span, null);
        };
      })(f);

      reader.readAsDataURL(f);
    }
  }
	
  document.getElementById('files').addEventListener('change', handleFileSelect, false);

  
var tog = true
$("#mypreview").click(function(){
	if(tog == true){
		tog = false;
		$("#name").text($("#exampleInputPassword1").val());
		$("#email").text($("#exampleInputEmail1").val());
		$("#text").text($("#content").val());
		$("#myForm").slideDown(2000);
		$("#myForm").removeAttr("hidden");
	}
	else{
		tog = true;
		$("#myForm").slideUp(2000);
	}
	
});
	
  
  
  
  
  
  
  
  
  
  
  
	
});
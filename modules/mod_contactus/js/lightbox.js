if (typeof contactus_module_id !== 'undefined') {
	var uploads_counter = uploads_counter || [];
	var module_ids = module_ids || [];
	module_ids.push(contactus_module_id);
	uploads_counter[contactus_module_id] = 0;
	
	function contactus_validate(element)
	{
		var inputs = element.getElementsByClassName("contactus-fields"),
			errorMessages = element.getElementsByClassName("contactus-error-message");
		for ( var i = errorMessages.length; i > 0; i-- ) {
				errorMessages[ i - 1].parentNode.removeChild( errorMessages[ i - 1] );
			}
		
		for (var i = 0; i < inputs.length; i++) {
			if ((inputs[i].hasAttribute("required")) &&(inputs[i].value.length == 0)) { 
				event.preventDefault();	
				parent = inputs[i].parentNode;
				parent.insertAdjacentHTML( "beforeend", "<div class='contactus-error-message'>" + 
				   type_field +
					"</div>" );
			}
		}	
	}
	function joomly_analytics(mod_id){
		if (contactus_params[mod_id].yandex_metrika_id) {
			// var yaCounter= new Ya.Metrika(contactus_params[mod_id].yandex_metrika_id);
			// yaCounter.reachGoal(contactus_params[mod_id].yandex_metrika_goal);
			// yaCounter46027323.reachGoal(yandex_metrika_goal);
			contactus_params[mod_id].yandex_metrika_id.reachGoal(yandex_metrika_goal);
		}
		if (contactus_params[mod_id].google_analytics_category) {
			//ga('send', 'event', contactus_params[mod_id].google_analytics_category, contactus_params[mod_id].google_analytics_action, contactus_params[mod_id].google_analytics_label, contactus_params[mod_id].google_analytics_value);
            dataLayer.push({'event': contactus_params[mod_id].google_analytics_category});
		}
	}	
	function contactus_uploader(mod_id){        
		var input = document.getElementById("file-input" + mod_id);
		var files = input.files;
		uploads_counter[mod_id] += files.length;
		var label = document.getElementById("file-label" + mod_id);
		var parent = document.getElementById("file-contactus" + mod_id);
		
		input.setAttribute("id", "");
		label.classList.add("contactus-added");
		
		new_input = document.createElement("input");
		new_input.setAttribute("type", "file");
		new_input.setAttribute("name", "file[]");
		new_input.setAttribute("multiple", "multiple");
		new_input.setAttribute("onchange", "contactus_uploader(" + mod_id + ")");
		new_input.setAttribute("class", "contactus-file");
		new_input.setAttribute("id", "file-input" + mod_id);

		parent.appendChild(new_input);

		if (uploads_counter[mod_id] > 1) {
			label.innerHTML = files_added + ": " + uploads_counter[mod_id];   
		}
		else {
			label.innerHTML = input.files[0].name.substr(0,30);
		}
	}
	function contactus_recaptcha(){
		var captchas = document.getElementsByClassName("g-recaptcha");
		for (var i=0; i < captchas.length; i++) {
			var sitekey = captchas[i].getAttribute("data-sitekey");
			if ((captchas[i].innerHTML === "") && (sitekey.length !== 0))
			{
				grecaptcha.render(captchas[i], {
		          'sitekey' : sitekey,
		          'theme' : 'light'
		        });		
			}
		}
	}
	window.addEventListener('load', function() { contactus_recaptcha();contactus_lightbox(module_ids); } , false); 
	function contactus_lightbox(m){	
		m.forEach(function(mod_id, i, arr) {		
			var opener1 = Array.prototype.slice.call(document.getElementsByClassName("contactus-" + mod_id));
			var opener2 = Array.prototype.slice.call((document.getElementsByClassName("contactus")));
			var opener = opener1.concat(opener2);
			var slider = document.getElementById('button-contactus-lightbox-form' + mod_id);
			for (var i=0; i < opener.length; i++) {

				opener[i].onclick = function(){
				
				var lightbox = document.getElementById("contactus-lightbox" + mod_id),
					dimmer = document.createElement("div"),
					close = document.getElementById("contactus-lightbox-close" + mod_id);
				
					dimmer.className = 'dimmer';
				
					dimmer.onclick = function(){
						if (slider) {
							slider.classList.toggle('closed');	
						}
						lightbox.parentNode.removeChild(dimmer);			
						lightbox.style.display = 'none';
					}
					
					close.onclick = function(){
						if (slider) {
							slider.classList.toggle('closed');	
						}	
						lightbox.parentNode.removeChild(dimmer);			
						lightbox.style.display = 'none';
					}
				
					if (slider) {
						slider.classList.toggle('closed');	
					}
							
					
					document.body.appendChild(dimmer);
					var scrollTop = window.pageYOffset || document.documentElement.scrollTop;
					lightbox.style.display = 'block';
					if (window.innerHeight > lightbox.offsetHeight ) {
						lightbox.style.top = scrollTop + (window.innerHeight- lightbox.offsetHeight)/2 + 'px';
					} else
					{
						lightbox.style.top = scrollTop + 10 + 'px';
					}
					if (window.innerWidth>400){
						lightbox.style.width = '400px';
						lightbox.style.left = (window.innerWidth - lightbox.offsetWidth)/2 + 'px';
					} else {
						lightbox.style.width = (window.innerWidth - 70) + 'px';
						lightbox.style.left = (window.innerWidth - lightbox.offsetWidth)/2 + 'px';
					}	
					
					return false;
				}
			}	
			if (contactus_sending_flag[mod_id] >= 1) {
				var lightbox = document.getElementById("contactus-sending-alert" + mod_id),
				dimmer = document.createElement("div"),
				close = document.getElementById("contactus-lightbox-sending-alert-close" + mod_id);
				
					dimmer.className = 'dimmer';
				
				dimmer.onclick = function(){
					lightbox.parentNode.removeChild(dimmer);			
					lightbox.style.display = 'none';
				}
				
				close.onclick = function(){
					lightbox.parentNode.removeChild(dimmer);			
					lightbox.style.display = 'none';
				}
					
				document.body.appendChild(dimmer);
				document.body.appendChild(lightbox);
				var scrollTop = window.pageYOffset || document.documentElement.scrollTop;
				lightbox.style.display = 'block';
				if (window.innerHeight > lightbox.offsetHeight ) {
					lightbox.style.top = scrollTop + (window.innerHeight- lightbox.offsetHeight)/2 + 'px';
				}
				else {
					lightbox.style.top = scrollTop + 10 + 'px';
				}
				if (window.innerWidth>400){
					lightbox.style.width = '400px';
					lightbox.style.left = (window.innerWidth - lightbox.offsetWidth)/2 + 'px';
				}
				else {
					lightbox.style.width = (window.innerWidth - 70) + 'px';
					lightbox.style.left = (window.innerWidth - lightbox.offsetWidth)/2 + 'px';
				}	
				
				setTimeout(remove_alert, 6000);
				
				function remove_alert()
				{
					lightbox.parentNode.removeChild(dimmer);			
					lightbox.style.display = 'none';
				}
			}	
			contactus_sending_flag[mod_id] = 0;	
		});	
		module_ids = [];
	}
}
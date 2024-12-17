function contactus_lightbox()
{
	if (typeof contactus_module_id !== 'undefined') {
		var module_ids = module_ids || [];
		var contactus_sending_flag = contactus_sending_flag || [];
		if (contactus_sending_flag[contactus_module_id] == undefined)
		{
			contactus_sending_flag[contactus_module_id] = getSendingFlag(contactus_module_id);
		}
		module_ids.push(contactus_module_id);

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
							if (slider)
							{
								slider.classList.toggle('closed');	
							}
							dimmer.parentNode.removeChild(dimmer);			
							lightbox.style.display = 'none';
						}
						
						close.onclick = function(){
							if (slider)
							{
								slider.classList.toggle('closed');	
							}	
							dimmer.parentNode.removeChild(dimmer);			
							lightbox.style.display = 'none';
						}
					
						if (slider)
						{
							slider.classList.toggle('closed');	
						}
								
						
						document.body.appendChild(dimmer);
						var scrollTop = window.pageYOffset || document.documentElement.scrollTop;
						lightbox.style.display = 'block';
						if (window.innerHeight > lightbox.offsetHeight )
						{
							lightbox.style.top = scrollTop + (window.innerHeight- lightbox.offsetHeight)/2 + 'px';
						} else
						{
							lightbox.style.top = scrollTop + 10 + 'px';
						}
						if (window.innerWidth > contactus_params[mod_id].form_max_width){
							lightbox.style.width = contactus_params[mod_id].form_max_width + 'px';
							lightbox.style.left = (window.innerWidth - lightbox.offsetWidth)/2 + 'px';
						} else {
							lightbox.style.width = (window.innerWidth - 70) + 'px';
							lightbox.style.left = (window.innerWidth - lightbox.offsetWidth)/2 + 'px';
						}	
						
						return false;
					}
				}	
				if (contactus_sending_flag[mod_id] >= 1){
					var lightbox = document.getElementById("contactus-sending-alert" + mod_id),
					dimmer = document.createElement("div"),
					close = document.getElementById("contactus-lightbox-sending-alert-close" + mod_id);
					
						dimmer.className = 'dimmer';
					
					dimmer.onclick = function(){
						dimmer.parentNode.removeChild(dimmer);			
						lightbox.style.display = 'none';
					}
					
					close.onclick = function(){
						dimmer.parentNode.removeChild(dimmer);			
						lightbox.style.display = 'none';
					}
						
					document.body.appendChild(dimmer);
					document.body.appendChild(lightbox);
					var scrollTop = window.pageYOffset || document.documentElement.scrollTop;
					lightbox.style.display = 'block';
					if (window.innerHeight > lightbox.offsetHeight )
					{
						lightbox.style.top = scrollTop + (window.innerHeight- lightbox.offsetHeight)/2 + 'px';
					} else
					{
						lightbox.style.top = scrollTop + 10 + 'px';
					}
					if (window.innerWidth> contactus_params[mod_id].form_max_width){
						lightbox.style.width = contactus_params[mod_id].form_max_width + 'px';  
						lightbox.style.left = (window.innerWidth - lightbox.offsetWidth)/2 + 'px';
					} else {
						lightbox.style.width = (window.innerWidth - 70) + 'px';
						lightbox.style.left = (window.innerWidth - lightbox.offsetWidth)/2 + 'px';
					}	
					
					setTimeout(function(){remove_alert(lightbox,dimmer)}, 6000);
					
				}	
				contactus_sending_flag[mod_id] = 0;	
			});	
			module_ids = [];
		}
	}
}

function contactus_validate(element)
{
	var inputs = element.getElementsByClassName("contactus-fields"),
		errorMessages = element.getElementsByClassName("contactus-error-message");
	for ( var i = errorMessages.length; i > 0; i-- ) {
			errorMessages[ i - 1].parentNode.removeChild( errorMessages[ i - 1] );
			console.log(i);
		}
	
	for (var i = 0; i < inputs.length; i++) {
		if ((inputs[i].hasAttribute("required")) &&(inputs[i].value.length == 0)) { 
			event.preventDefault();	
			parent = inputs[i].parentNode;
			parent.insertAdjacentHTML( "beforeend", "<div class='contactus-error-message'>" + 
			   type_field +
				"</div>" );
				console.log("ad" + i)
		}
	}	
}
function joomly_analytics(mod_id){
	if (contactus_params[mod_id].yandex_metrika_id)
	{
		var yaCounter= new Ya.Metrika(contactus_params[mod_id].yandex_metrika_id);
		yaCounter.reachGoal(contactus_params[mod_id].yandex_metrika_goal);
	}
	if (contactus_params[mod_id].google_analytics_category)
	{
		// ga('send', 'event', contactus_params[mod_id].google_analytics_category, contactus_params[mod_id].google_analytics_action, contactus_params[mod_id].google_analytics_label, contactus_params[mod_id].google_analytics_value);
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

	if (uploads_counter[mod_id] > 1)
	{
		label.innerHTML = files_added + ": " + uploads_counter[mod_id];   
	} else
	{
		label.innerHTML = input.files[0].name.substr(0,30);
	}
}
function contactus_recaptcha(){
	var captchas = document.getElementsByClassName("g-recaptcha");
	for (var i=0; i < captchas.length; i++) {
		var sitekey = captchas[i].getAttribute("data-sitekey");
		var size= captchas[i].getAttribute("data-size");
		if ((captchas[i].innerHTML === "") && (sitekey.length !== 0))
		{
			grecaptcha.render(captchas[i], {
	          'sitekey' : sitekey,
	          'theme' : 'light',
	          'size' : size
	        });		
		}
	};
}
function getCookie(name) {
  var matches = document.cookie.match(new RegExp(
	"(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
  ));
  return matches ? decodeURIComponent(matches[1]) : undefined;
}
function getSendingFlag(m_id){
	var sendingalert = getCookie("sending-alert"),
		alerttype = getCookie("alert-type"),
		sflag = 0;
		//console.log(sendingalert, alerttype);
	if ((typeof sendingalert !== 'undefined') && (sendingalert == m_id))
	{
		al = document.getElementById("contactus-sending-alert" + m_id);
		if (alerttype == 'success')
		{
			sflag = 1;
		} else if (alerttype == 'captcha')
		{
			sflag = 2;
			al.childNodes[1].style.backgroundColor = "red";
			al.childNodes[3].childNodes[1].innerHTML = captcha_error;
		} else if (alerttype == 'file')
		{
			sflag = 3;
			al.childNodes[1].style.backgroundColor = "red";
			al.childNodes[3].childNodes[1].innerHTML = filesize_error;
		}
		document.cookie = 'sending-alert=333; Path=/;expires=Thu, 01 Jan 1970 00:00:01 GMT;';
		document.cookie = 'alert-type=;Path=/; expires=Thu, 01 Jan 1970 00:00:01 GMT;';
	} else 
	{
		sflag = 0;
	}	
	return sflag;
}
function remove_alert(lightbox, dimmer)
{
	if (lightbox.style.display  != "none")
	{
		dimmer.parentNode.removeChild(dimmer);			
		lightbox.style.display = 'none';
	}
}
function set_dependencys(dependencys)
{
	for(var key in dependencys) {
		var fields = document.getElementsByClassName('field' + key);
		for (var i=0; i < fields.length; i++) {
			fields[i].onchange = function(){
				tag = this.tagName.toLowerCase();
				element = this;
				tag = (tag == "input") ? this.type : tag;
				if (tag == "select")
				{
					comparsion = this.selectedIndex;
				} else if (tag  == "checkbox")
				{
					comparsion = (this.checked) ? 1 : 0;
				} else 
				{
					comparsion = this.value;
				}
				dependencys[this.getAttribute('data-id')].forEach(function(entry) {
					if (comparsion == entry.value)
					{
						element.parentNode.parentNode.parentNode.getElementsByClassName(entry.child)[0].parentNode.style.display= 'block';
					} else 
					{
						element.parentNode.parentNode.parentNode.getElementsByClassName(entry.child)[0].parentNode.style.display= 'none';
					}
				});
			}
			fields[i].onchange();
		}
	}
}
function submitForm() {
	document.getElementById("contactusForm").submit();
}
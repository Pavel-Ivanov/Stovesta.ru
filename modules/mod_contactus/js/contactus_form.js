function contactus_form()
{
	if (typeof contactus_module_id === 'undefined') {
		return;
	}
	var form_ids = form_ids || [];
	var contactus_sending_flag = contactus_sending_flag || [];
	if (contactus_sending_flag[contactus_module_id] == undefined) {
		contactus_sending_flag[contactus_module_id] = getSendingFlag(contactus_module_id);
	}
	form_ids.push(contactus_module_id);

	function contactus_form(m) {
		m.forEach(function (mod_id, i, arr) {
			forms = document.getElementsByClassName("contactus-form" + mod_id);
			for (var j = 0; j < forms.length; j++) {
				forms[j].style.maxWidth = contactus_params[mod_id].form_max_width + 'px';
			}
			if (contactus_sending_flag[mod_id] >= 1) {
				var lightbox = document.getElementById("contactus-sending-alert" + mod_id),
					dimmer = document.createElement("div"),
					close = document.getElementById("contactus-lightbox-sending-alert-close" + mod_id);
				dimmer.className = 'dimmer';

				dimmer.onclick = function () {
					dimmer.parentNode.removeChild(dimmer);
					lightbox.style.display = 'none';
				}

				close.onclick = function () {
					dimmer.parentNode.removeChild(dimmer);
					lightbox.style.display = 'none';
				}

				document.body.appendChild(dimmer);
				document.body.appendChild(lightbox);
				var scrollTop = window.pageYOffset || document.documentElement.scrollTop;
				lightbox.style.display = 'block';
				if (window.innerHeight > lightbox.offsetHeight) {
					lightbox.style.top = scrollTop + (window.innerHeight - lightbox.offsetHeight) / 2 + 'px';
				} else {
					lightbox.style.top = scrollTop + 10 + 'px';
				}
				if (window.innerWidth > 400) {
					lightbox.style.width = '400px';
					lightbox.style.left = (window.innerWidth - lightbox.offsetWidth) / 2 + 'px';
				} else {
					lightbox.style.width = (window.innerWidth - 70) + 'px';
					lightbox.style.left = (window.innerWidth - lightbox.offsetWidth) / 2 + 'px';
				}

				setTimeout(function () {
					remove_alert(lightbox, dimmer)
				}, 6000);

			}
			contactus_sending_flag[mod_id] = 0;
		});
		form_ids = [];
	}
}

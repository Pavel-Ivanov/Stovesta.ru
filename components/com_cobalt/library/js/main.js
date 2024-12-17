var Cobalt = {};
var _gaq = _gaq || [];

(function($) {
	var floatnum = [];

	Cobalt.typeahead = function(el, post, options) {
		if(!$(el).length) return;

		var labels = [], mapped = [];

		options = options || {};

		$(el).typeahead({
			items:   options.limit || 10,
			source:  function(query, process) {
				post.q = query;
				post.limit = options.limit || 10;
				return $.get(options.url || Cobalt.field_call_url, post, function(data) {
					if(!data) return;
					if(!data.result) return;

					labels[el] = [];
					mapped[el] = {};

					$.each(data.result, function(i, item) {
						mapped[el][item.label] = item.value
						labels[el].push(item.label)
					});

					return process(labels[el]);
				}, 'json');
			},
			updater: function(item) {
				return mapped[el][item];
			}
		});
	};

	Cobalt.formatInt = function(el) {
		var cur = el.value;
		reg = /[^\d]+/;
		cur = cur.replace(reg, "");
		el.value = cur;
	};

	Cobalt.formatFloat = function(obj, decimal, max, val_max, val_min, field_id, msg) {
		if(floatnum[obj.id] == obj.value) {
			return;
		}

		var cur = obj.value;

		cur = cur.replace(',', '.');
		cur = cur.replace('..', '.');

		var sign = '';

		if(cur.indexOf('-') == 0) {
			sign = '-';
			cur = cur.substr(1, cur.length);
		} else if(cur.indexOf('+') == 0) {
			sign = '+';
			cur = cur.substr(1, cur.length);
		}

		if(decimal > 0) {
			reg = /[^\d\.]+/;
		} else {
			reg = /[^\d]+/;
		}
		cur = cur.replace(reg, '');

		cur = sign + cur;

		if((cur.lastIndexOf('.') >= 0) && (cur.indexOf('.') > 0) && (cur.indexOf('.') < cur.lastIndexOf('.'))) {
			reg2 = /\.$/;
			cur = cur.replace(reg2, '');
		}

		if(cur) {

			var myRe = /^([^\.]+)(.*)/i;
			var myArray = myRe.exec(cur);
			number = myArray[1];
			rest = myArray[2];

			if(number.length > decimal) {
				cur = number.substr(0, max) + rest;
			}

			if(decimal > 0 && (cur.indexOf('.') > 0)) {
				myRe = /([^\.]+)\.([^\.]*)/i;
				myArray = myRe.exec(cur);
				number = myArray[1];
				float = myArray[2];

				if(float.length > decimal) {
					cur = number + '.' + float.substr(0, decimal);
				}
			}

			if(val_max && val_min) {
				if(parseFloat(cur) > val_max) {
					cur = val_max;
					Cobalt.fieldError(field_id, msg);
				}
				if(parseFloat(cur) < val_min) {
					cur = val_min;
					Cobalt.fieldError(field_id, msg);
				}
			}
		}

		obj.value = cur;
		floatnum[obj.id] = obj.value;
	};

	Cobalt.redrawBS = function() {
		$('*[rel^="tooltip"]').tooltip();
		$('*[rel="popover"]').popover();
		$('.tip-bottom').tooltip({placement: "bottom"});

		jQuery('.radio.btn-group label').addClass('btn');
		jQuery(".btn-group label:not(.active)").click(function() {
			var label = jQuery(this);
			var input = jQuery('#' + label.attr('for'));

			if(!input.prop('checked')) {
				label.closest('.btn-group').find("label").removeClass('active btn-success btn-danger btn-primary');
				if(input.val() === '') {
					label.addClass('active btn-primary');
				} else if(input.val() == 0) {
					label.addClass('active btn-danger');
				} else {
					label.addClass('active btn-success');
				}
				input.prop('checked', true);
			}
		});
		jQuery(".btn-group input[checked=checked]").each(function() {
			if(jQuery(this).val() === '') {
				jQuery("label[for=" + jQuery(this).attr('id') + "]").addClass('active btn-primary');
			} else if(jQuery(this).val() == 0) {
				jQuery("label[for=" + jQuery(this).attr('id') + "]").addClass('active btn-danger');
			} else {
				jQuery("label[for=" + jQuery(this).attr('id') + "]").addClass('active btn-success');
			}
		});
	};

	Cobalt.setAndSubmit = function(el, val) {
		var elm = jQuery('#' + el);
		elm.val(val).attr('selected', true);
		elm.parents('form').submit();
	};

	Cobalt.checkAndSubmit = function(el) {
		var elm = jQuery(el);
		elm.attr('checked', true);
		setTimeout(function() {
			elm.parents('form').submit();
		}, 200);
	};

	Cobalt.yesno = function(yes, no) {
		let y = $(yes);
		let n = $(no);
		y.on('click', function() {
			y.addClass('btn-primary');
			n.removeClass('btn-primary');
			$('input[type="radio"]', n).removeAttr('checked', 'checked');
			$('input[type="radio"]', y).attr('checked', 'checked');
		});
		n.on('click', function() {
			n.addClass('btn-primary');
			y.removeClass('btn-primary');
			$('input[type="radio"]', y).removeAttr('checked', 'checked');
			$('input[type="radio"]', n).attr('checked', 'checked');
		});

	}

	Cobalt.ItemRatingCallBackMulti = function(vote, ident, index) {
		Cobalt.ItemRatingCallBackSingle(vote, ident, index, true);
	};

	Cobalt.ItemRatingCallBackSingle = function(vote, ident, index, multi) {
		var old_html = $('#rating-text-' + ident).html();

		$('#rating-text-' + ident).addClass('progress progress-striped active').html('<div class="bar" style="width: 100%;"><?php echo JText::_("CPROCESS") ?></div>');
		$.ajax({
			url:      '<?php echo JRoute::_("index.php?option=com_cobalt&task=rate.record&tmpl=component", FALSE); ?>',
			dataType: 'json',
			type:     'POST',
			data:     {vote: vote, id: ident, index: index}
		}).done(function(json) {
			$('#rating-text-' + ident).removeClass('progress progress-striped active').html('&nbsp;');

			if(!json) {
				return;
			}

			if(!json.success) {
				$('#rating-text-' + ident).html(old_html);
				alert(json.error);
				return;
			}

			$('#rating-text-' + ident).html('<?php echo JText::sprintf("CRAINGDATA", "' + json.result + '", "' + json.votes + '");?>');

			if(json.result) {
				if(multi) {
					var fname = eval('newRating' + index + '_' + ident);
					fname.setCurrentStar(vote);
				}

				var fname = eval('newRating500_' + ident);
				fname.setCurrentStar(json.result);
			}
			if(_gaq) {
				_gaq.push(['_trackEvent', 'Record', 'Rated', json.name, vote]);
			}
		});
	};

	Cobalt.cleanFilter = function(name) {
		$('#' + name).val(1);
		Joomla.submitbutton('records.clean');
	};

	Cobalt.applyFilter = function(name, val, type) {
		var el = $('#adminForm');

		if(type) {
			var inp3 = $(document.createElement('input'))
				.attr('type', 'hidden')
				.attr('value', 'filter_type')
				.attr('name', 'filter_name[1]');

			var inp4 = $(document.createElement('input'))
				.attr('type', 'hidden')
				.attr('value', type)
				.attr('name', 'filter_val[1]');
			el.append(inp3);
			el.append(inp4);
		}

		var inp1 = $(document.createElement('input'))
			.attr('type', 'hidden')
			.attr('value', name)
			.attr('name', 'filter_name[0]');

		var inp2 = $(document.createElement('input'))
			.attr('type', 'hidden')
			.attr('value', val)
			.attr('name', 'filter_val[0]');

		el.append(inp1);
		el.append(inp2);

		Joomla.submitbutton('records.filter');
		if(_gaq) {
			_gaq.push(['_trackEvent', 'Filter', name, val]);
		}
	};

	Cobalt.showAddForm = function(id) {
		let link = $('#show_variant_link_' + id).clone();
		let data = JSON.decode(link.attr('rel'));
		let container = $('#variant_' + id);

		let input = $(document.createElement('input'))
			.attr('name', 'your_variant_' + data.id)
			.attr('type', 'text');
		let ba = $(document.createElement('button'))
			.attr('type', 'button')
			.attr('class', 'uk-button uk-button-success uk-margin-left')
			.html('<i class="uk-icon-plus"></i>');
		let bc = $(document.createElement('button'))
			.attr('type', 'button')
			.attr('class', 'uk-button uk-button-danger')
			.html('<i class="uk-icon-minus"></i>');

		let bg = $(document.createElement('div')).attr('class', 'input-append')
			.append(input).append(ba).append(bc);

		bc.click(function(el) {
			input.val('');
			container.html('');
			container.append(link);
		});

		ba.click(function(el) {
			let inpt;
			if(!input.val()) {
				alert('Введите значение');
				return;
			}

			let inpname = 'jform[fields][' + data.id + ']';
			if(data.inputtype === 'checkbox') {
				inpname += '[]';
			}

			if(data.inputtype === 'option') {
				inpt = $(document.createElement('option'))
					.attr('value', input.val())
					.attr('selected', 'selected')
					.html(input.val())
					.click(function () {
						Cobalt.countFieldValues(this, data.id, data.limit, data.inputtype);
					});

				let sel = $('#form_field_list_' + data.id);

				sel.append(inpt);
				sel.trigger("liszt:updated");

				if(data.field_type === 'multiselect') {
					sel.attr('size', (parseInt(sel.attr('size')) + 1));
				}
			}
			else {
				inpt = $(document.createElement('input'))
					.attr({
						value:    input.val(),
						selected: 'selected',
						checked:  'checked',
						type:     data.inputtype,
						name:     inpname
					})
					.click(function() {
						Cobalt.countFieldValues(this, data.id, data.limit, data.inputtype);
					});

				$(document.createElement('div')).attr({'class': 'row-fluid'})
					.append($(document.createElement('div'))
						.attr({'class': 'span12'})
						.append($(document.createElement('label'))
							.attr({'class': data.inputtype})
							.append(inpt, input.val())))
					.appendTo($('#elements-list-' + data.id));
			}

			Cobalt.countFieldValues(inpt, data.id, data.limit, data.inputtype);
			bc.trigger('click');
		});

		container.html('');
		container.append(bg);
	};

	Cobalt.countFieldValues = function(val, field_id, limit, type) {
		Cobalt.fieldError(field_id);
		if(limit <= 0) {
			return;
		}

		var field = $('[name^=jform\\[fields\\]\\[' + field_id + '\\]]');
		var selected = 0;
		if(type === 'checkbox') {
			$.each(field, function(key, obj) {
				if(obj.checked) {
					selected++;
				}
			});
		}
		if(type === 'option') {
			$.each(field[0].options, function(key, obj) {
				if(obj.selected) {
					selected++;
				}
			});
		}
		if(type === 'select') {
			selected = val.getSelected().length;
		}

		if(selected > limit) {

			var msg = '<?php echo JText::sprintf("CERRJSMOREOPTIONS")?>';
			Cobalt.fieldError(field_id, msg);

			if(type === 'checkbox') {
				val.removeAttr('checked', '');
			}
			else if(type === 'option') {
				val.removeAttr('selected', '');
			}
			else if(type === 'select') {
				$.each(val.getSelected(), function(k, v) {
					if(k + 1 > limit) {
						v.selected = false;
					}
				});
			}
		}
	};

	Cobalt.fieldError = function(id, msg) {
		var box = $('#field-alert-' + id);
		var control = box.closest('.control-group');

		if(msg) {
			box.html(msg);
			box.slideDown('quick', function() {
				control.addClass('error').click(function() {
					Cobalt.fieldErrorClear(id);
				});
			});
		} else {
			Cobalt.fieldErrorClear(id);
		}
	};

	Cobalt.fieldErrorClear = function(id) {
		var box = $('#field-alert-' + id);
		var control = box.closest('.control-group');

		box.html('').slideUp('quick');
		control.unbind('click').removeClass('error');
	};


	Cobalt.setAndSubmit = function(el, val) {
		var elm = $('#' + el);
		elm.val(val);
		elm.parents('form').submit();
	};

	Cobalt.field_call_url = '<?php echo JRoute::_("index.php?option=com_cobalt&task=ajax.field_call&tmpl=component", FALSE);?>';

}(jQuery));


function trackComment(comment, id) {
	jQuery.ajax({
		url:  '<?php echo JRoute::_("index.php?option=com_cobalt&task=ajax.trackcomment&tmpl=component", FALSE); ?>',
		data: {
			record_id: id
		}
	});
}

function getSelectionHtml() {
	var html = "";
	if(typeof window.getSelection != "undefined") {
		var sel = window.getSelection();
		if(sel.rangeCount) {
			var container = document.createElement("div");
			for(var i = 0, len = sel.rangeCount; i < len; ++i) {
				container.appendChild(sel.getRangeAt(i).cloneContents());
			}
			html = container.innerHTML;
		}
	} else if(typeof document.selection != "undefined") {
		if(document.selection.type === "Text") {
			html = document.selection.createRange().htmlText;
		}
	}
	return html;
}

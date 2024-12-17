!(function($){
	"use strict";

	function Description(options) {

		var self = this;

		options = options || {};

		this.limit	= options.limit || 0;
		this.id		= options.id || 0;
		this.alert	= options.limit_alert || 'You have reached the limits';
		this.label	= options.labels || 0;
		this.default_labels	= options.default_labels || 'Label';
		this.label_change	= options.labels_change || 0;
		this.label1	= options.label1 || 'Url';
		this.label2	= options.label2 || 'Label';
		this.values = [];
		this.num = 0;

		$('#add-description'+ this.id).click( function() {
			if(!self.checkLimit()){
				alert(self.alert);
				return;
			}
			self.createBlock('', self.default_labels);
		});
		this.checkLimit();
	};

	Description.prototype.checkLimit = function(){
		if(this.limit && this.num >= this.limit){
			$("#add-description"+this.id).hide();
			return false;
		}else{
			$("#add-description"+this.id).show();
			return true;
		}
	};

	var old_val = '';

	/*
    Description.prototype.keyup = function(input, key) {
		//var value = clean(input.value);
        var value = input.value;
		if(old_val == value) {
			return;
		}
		old_val = value;
		if( ((value != input.value) || (value != this.values[key])) && this.label_change)
		{
			$($(input).parent('div.url-item').children('input')[1]).val('');
		}
		this.values[key] = value;
		input.value = value;
	};
	*/

	Description.prototype.createBlock = function(url, labels) {

		this.num++;
		var self = this;
		var list = $('#url-list' + this.id);
		var key = $('#url-list'+ this.id).children('div.url-item').length;
		var container = $(document.createElement('div')).attr('class', 'url-item row-fluid span12').css('margin-bottom', '15px');
		this.values[key] = '';

		$('<button type="button" class="btn pull-right btn-micro btn-danger"><i class="icon-remove"></i></button>')
            .click(function(){
			container.slideUp('fast', function(){
				$(this).remove();
				self.num--;
				self.checkLimit();
			});
		})
            .appendTo(container);

        if($.isArray(labels))
        {
            var j = this.num - 1;
            if(this.num > labels.length)
            {
                j = labels.length - 1;
            }
            var label = labels[j];
        }
        else
        {
            var label = labels;
        }

        if(this.label) {
            container.append('<strong>' + this.label1 + ':</strong> ');
            container.append('<input '+ (!this.label_change ? 'readonly="readonly"' : '') +' type="text" name="jform[fields][' + this.id + '][' + key + '][label]" value="'+label+'" id="url' + this.id + '-label'+ key +'">');
        }

        container.append(' <strong>' + this.label2 + ' :</strong> ');

		var input = $(document.createElement('input')).attr({
			type:  "text",
			name:  "jform[fields][" + this.id + "][" + key + "][url]",
			value: url
		    })
            /*
            .bind('keyup', function(){
                self.keyup(this, key);
		    })
		    */
            .appendTo(container);

		list.append(container);
		list.append('<div class="clearfix"></div>');
		this.checkLimit();
	};

	window.cobaltDescriptionField = Description;

}(jQuery));
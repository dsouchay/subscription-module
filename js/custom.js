	Drupal.behaviors.mailingSubscription = {
		attach : function(context, settings) {
			jQuery('#dialog').dialog({
				autoOpen: true,
				open: function (event, ui) {
					jQuery(event.target).dialog('widget')
						.css({ position: 'fixed' })
						.position({ my: 'right bottom', at: 'right bottom', of: window,  });
				},
				resizable: false
			});
		}
	};


Drupal.behaviors.cambiarCheck = {
	attach: function (context, settings) {
		jQuery('.ui-dialog input[type="radio"]')
			.filter("[checked='checked']").next().addClass("marcado");

		jQuery('.ui-dialog input[type="radio"]')
			.click(function (event) {
				jQuery('.ui-dialog input[type="radio"]').next().removeClass("marcado");
				jQuery(this).next().toggleClass("marcado");
			});

	}
};




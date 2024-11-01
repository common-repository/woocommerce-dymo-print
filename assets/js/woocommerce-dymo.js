jQuery(document).ready(function($) {
	$('.dymo-link').click(function (e){
		e.preventDefault();
		var OrderID=$(this).data('dymo-id');
		
		$(this).parent().find('.dymo-progress').css("display", "block");
		
		setTimeout(function() {
			frameworkInitShim(OrderID);
		},500);

  });

});

function WCDymostartupCode(OrderID) {
	var progress_bar=jQuery('.dymo-progress-'+OrderID);
	var errorbox=jQuery('.wc-dymo-errors');
	jQuery(progress_bar).animate({opacity:1},500);

	var printers=dymo.label.framework.getPrinters();
		var printParams = {};

		if (printers.length != 0) {

			var xml= dymo.label.framework.openLabelFile(dymo_data.template);
			var label = dymo.label.framework.openLabelXml(xml);

			var printer=printers[0];

			if(!printer.isConnected) {
				printer=printers[1];
			}

			if (typeof printer.isTwinTurbo != "undefined") {
				if (printer.isTwinTurbo) {
					printParams.twinTurboRoll = dymo.label.framework.TwinTurboRoll.Auto; // or Left or Right
				}
			}

			function handleErrors(response) {
				if (!response.ok) {
					throw Error(response.statusText);
				}
				return response;
			}

			if(printer.isConnected && label.isValidLabel()) {

				var ajax_data = {
					'security': dymo_data.ajax_nonce,
					'action': 'dymo_get_order_data',
					'order_id': OrderID
				};

				jQuery.ajax({
					url:ajaxurl,
					data: ajax_data,
					dataType: 'json',
					success: function(response) {

						var address=response.address;
						address= address.replace(/\|/g, "\n");

                        label.setObjectText("COMPANY", dymo_data.company+' ');
						/*if(dymo_data.company!="" && dymo_data.company!=null) {
							label.setObjectText("COMPANY", dymo_data.company);
						}*/

                        var extra=dymo_data.extra;
                        extra=extra.replace('[order]',response.order_number);

                        label.setObjectText("EXTRA", extra+ ' ');

                        /*
						if(dymo_data.extra!=""&& dymo_data.extra!=null) {

							var extra=dymo_data.extra;
							extra=extra.replace('[order]',response.order_number);

							label.setObjectText("EXTRA", extra+ ' ');
						}*/

						label.setObjectText("ADDRESS", address);

						setTimeout(function() {
							errorbox.hide();

							printParams.copies=1;
							printParams.jobTitle=dymo_data.job_title + response.order_number;

							try {
								label.print(printer.name, dymo.label.framework.createLabelWriterPrintParamsXml(printParams));
								wp.a11y.speak(dymo_data.label_printed + ' '+ dymo_data.order + ' ' + response.order_number, 'polite' );
							} catch (ex) {
								console.log(ex);
								get_dymo_error(errorbox,dymo_data.general_error,OrderID);
								get_dymo_error(errorbox,ex.message,OrderID);
							}
							progress(100, progress_bar);
						},2000);
					},error: function(data) {
						get_dymo_error(errorbox,dymo_data.general_error,OrderID);
					}
				});
			} else {
				get_dymo_error(errorbox,dymo_data.no_printers_connected,OrderID);
			}
		} else {
			get_dymo_error(errorbox,dymo_data.no_printers_installed,OrderID);
		}

}

function get_dymo_error($element,message,OrderID) {
	if(jQuery('body').hasClass('edit-php')) {
		$element.show().html('<p>'+message+'</p>');
	} else {
		$element.show().append('<p>'+message+'</p>');
	}

	jQuery('.dymo-progress-'+OrderID).addClass('dymo-progress-error');
	wp.a11y.speak( message, 'polite' );
}

function frameworkInitShim(OrderID) {
		dymo.label.framework.trace = 1
		var errorbox=jQuery('.wc-dymo-errors');

		window.addEventListener('error', function (evt) {
			get_dymo_error(errorbox,dymo_data.no_framework,OrderID);
			get_dymo_error(errorbox,evt.message,OrderID);
			console.log(evt.message);

			evt.preventDefault();
		});

		try {
			dymo.label.framework.init(WCDymostartupCode(OrderID));
		} catch (ex) {
			console.log(ex);
		}
}

function progress(percent, $element) {

	var progressBarWidth = percent * $element.width() / 100;
	percent=percent.toFixed(0);
	$element.find('span').animate({ width: progressBarWidth }, 3000).html(percent + "%&nbsp;");

	setTimeout(function() {
		$element.animate({opacity:0},500);
	},10000);
}

jQuery(document).on( 'click', '.dymo-update-notice .notice-dismiss', function() {
    jQuery.ajax({
        url: ajaxurl,
        data: {
            action: 'dymo_dismiss_update_notice'
        }
    })

})

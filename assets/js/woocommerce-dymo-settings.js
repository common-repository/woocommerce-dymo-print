(function()
{
	var _label;
	var _labelData = { ADDRESS: objects.last_address,COMPANY: objects.companyText+ '\u0020',EXTRA: '\u0020'+objects.extraText };

    // Setup defaultLayout for preview
	function setupDefaultLayout()
    {
		var xml= dymo.label.framework.openLabelFile(objects.template);
		_label = dymo.label.framework.openLabelXml(xml);

		applyDataToLabel(_label, _labelData);
    }


	// updates label preview image
    // Generates label preview and updates corresponend <img> element
    function updatePreview()
    {
        if (!_label)
            return;

        var pngData = _label.render();

        var labelImage = document.getElementById('labelImage');
        labelImage.src = "data:image/png;base64," + pngData;
    }

	// applies data to the label
    function applyDataToLabel(label, labelData)
    {
        var names = label.getObjectNames();

        for (var name in labelData) {
            if (itemIndexOf(names, name) >= 0) {
				label.setObjectText(name, labelData[name]);
			}
		}
    }

	// returns an index of an item in an array. Returns -1 if not found
    function itemIndexOf(array, item)
    {
        for (var i = 0; i < array.length; i++)
            if (array[i] == item) return i;

        return -1;
    }


	// Load all printers for printers tab and debug tab
	function loadPrinters()
    {
		//Create table for debug information
		var table = document.createElement("table");
		table.className='dymocheck';

		// Create the header row of <th> elements in a <tr> in a <thead>
		var thead = document.createElement("thead");
		var header = document.createElement("tr");

		var createTableHeader = function(name)
		{
			var cell = document.createElement("th");
			cell.appendChild(document.createTextNode(name));
			header.appendChild(cell);
		};

		createTableHeader(objects.printerType);
		createTableHeader(objects.printername);
		createTableHeader(objects.modelname);
		createTableHeader(objects.local);
		createTableHeader(objects.connected);
		createTableHeader("TwinTurbo");

		// Put the header into the table
		thead.appendChild(header);
		table.appendChild(thead);

		// The remaining rows of the table go in a <tbody>
		var tbody = document.createElement("tbody");
		table.appendChild(tbody);

		var createPrinterRow = function(printer, row, propertyName)	{
			var cell = document.createElement("td");

			// Put the text data into the HTML cell
			if (typeof printer[propertyName] != "undefined")
				cell.appendChild(document.createTextNode(printer[propertyName]));
			else
				cell.appendChild(document.createTextNode("n/a"));
				// Add the cell to the row
				row.appendChild(cell);
		};


        // Load printers Async
		dymo.label.framework.getPrintersAsync().then(function(printers) {
            var hideDebugButtons=false;
			if (printers.length != 0) {
                console.log(printers);
                console.log('Aantal printers: '+printers.length)
				for (var i = 0; i < printers.length; i++)
				{
					var printerName = printers[i].name;
					if(printers[i].isConnected) {
						jQuery('#printersSelect').append('<option value="' + printerName+ '">'+printerName+'</option>');

						var printer = printers[i];
						// Create an HTML element to display the data in the row
						var row = document.createElement("tr");

						createPrinterRow(printer, row, "printerType");
						createPrinterRow(printer, row, "name");
						createPrinterRow(printer, row, "modelName");
						createPrinterRow(printer, row, "isLocal");
						createPrinterRow(printer, row, "isConnected");
						createPrinterRow(printer, row, "isTwinTurbo");

						// And add the row to the tbody of the table
						tbody.appendChild(row);

						add_log('-','ok');
						if(printer.isTwinTurbo) {
							add_log('Printer type: '+printer.printerType+' TwinTurbo','ok');
						}
						add_log('Success: Found connected printer '+printerName,'ok');
						jQuery('#printButton,#printersSelect').fadeIn(200);

                        hideDebugButtons=false;

					} else {
						add_log(objects.clearCache);
						add_log('Error: '+printerName+ ' installed, but not connected. Try to reconnect this printer.','error');
					}
				}
			} else {
				add_log('Error: No supported printers found.','error');
				jQuery('#printButton,#printersSelect').hide();
                hideDebugButtons=true;
			}

            // If no printers are connected hide debug print buttons (as no information can be printed)
            if(hideDebugButtons) {
                jQuery('#printButton,#printersSelect').hide();
            }
		});
		return table;

    }

	// updates printers information and insert it into the document
    function updatePrintersTable()
    {

		jQuery('#printersSelect option').remove();
        var container = document.getElementById("printersInfoContainer");

        // remove previous table
        while (container.firstChild)
            container.removeChild(container.firstChild);

        container.appendChild(loadPrinters());

    }

	// Print printers information on label
	// Possible lable layouts
    var dieCutLabelLayout = '<?xml version="1.0" encoding="utf-8"?>\<DieCutLabel Version="8.0" Units="twips">\<PaperOrientation>Landscape</PaperOrientation>\<Id>Address</Id>\<PaperName>30252 Address</PaperName>\<DrawCommands/>\<ObjectInfo>\    <TextObject>\        <Name>Text</Name>\        <ForeColor Alpha="255" Red="0" Green="0" Blue="0" />\        <BackColor Alpha="0" Red="255" Green="255" Blue="255" />\        <LinkedObjectName></LinkedObjectName>\        <Rotation>Rotation0</Rotation>\        <IsMirrored>False</IsMirrored>\        <IsVariable>True</IsVariable>\        <HorizontalAlignment>Left</HorizontalAlignment>\        <VerticalAlignment>Middle</VerticalAlignment>\        <TextFitMode>AlwaysFit</TextFitMode>\        <UseFullFontHeight>True</UseFullFontHeight>\        <Verticalized>False</Verticalized>\        <StyledText/>\    </TextObject>\    <Bounds X="332" Y="150" Width="4455" Height="1260" />\</ObjectInfo>\
    </DieCutLabel>';

    var continuousLabelLayout = '<?xml version="1.0" encoding="utf-8"?>\<ContinuousLabel Version="8.0" Units="twips">\    <PaperOrientation>Landscape</PaperOrientation>\<Id>Tape19mm</Id>\    <PaperName>19mm</PaperName>\    <LengthMode>Auto</LengthMode>\    <LabelLength>0</LabelLength>\    <RootCell>\    <TextObject>\        <Name>Text</Name>\        <ForeColor Alpha="255" Red="0" Green="0" Blue="0" />\        <BackColor Alpha="0" Red="255" Green="255" Blue="255" />\        <LinkedObjectName></LinkedObjectName>\        <Rotation>Rotation0</Rotation>\        <IsMirrored>False</IsMirrored>\        <IsVariable>True</IsVariable>\        <HorizontalAlignment>Left</HorizontalAlignment>\        <VerticalAlignment>Middle</VerticalAlignment>\        <TextFitMode>AlwaysFit</TextFitMode>\        <UseFullFontHeight>False</UseFullFontHeight>\        <Verticalized>False</Verticalized>\        <StyledText/>\    </TextObject>\    <ObjectMargin Left="0" Top="0" Right="0" Bottom="0" />\<Length>0</Length>\<LengthMode>Auto</LengthMode>\<BorderWidth>0</BorderWidth>\<BorderStyle>Solid</BorderStyle>\<BorderColor Alpha="255" Red="0" Green="0" Blue="0" />\
    </RootCell>\</ContinuousLabel>';

    // prints printers information
    function print(printerName)
    {
        dymo.label.framework.getPrintersAsync().then(function(printers) {

        var printer = printers[printerName];
        if (!printer)
        {
            alert("Printer '" + printerName + "' not found");
            return;
        }

        // select label layout/template based on printer type
        var labelXml;
        if (printer.printerType == "LabelWriterPrinter")
            labelXml = dieCutLabelLayout;
        else if (printer.printerType == "TapePrinter")
            labelXml = continuousLabelLayout;
        else
        {
            alert("Unsupported printer type");
            throw "Unsupported printer type";
        }

        // create label set to print printers' data
        var labelSetBuilder = new dymo.label.framework.LabelSetBuilder();
        for (var i = 0; i < printers.length; i++)
        {
            var printer = printers[i];

            // process each printer info as a separate label
            var record = labelSetBuilder.addRecord();

            // compose text data
            // use framework's text markup feature to set text formatting
            // because text markup is xml you can use any xml tools to compose it
            // here we will use simple text manipulations t oavoid cross-browser compatibility.
            var info = "<font family='Courier New' size='14'>"; // default font
            info = info + objects.printer+" <b>" + printer.name + "\n</b>";
            info = info + objects.printerType + ': '+ printer.printerType;
            info = info + "\n<font size='10'>"+objects.is_local+": " + printer.isLocal;
            info = info + "\n"+objects.is_online+" " + printer.isConnected + "</font>";

            if (typeof printer.isTwinTurbo != "undefined")
            {
                if (printer.isTwinTurbo) {
                    info = info + "<i><u><br/>"+objects.twinturbo+"</u></i>";
				}else {
                    info = info + "<font size='6'><br/>"+objects.no_twinturbo+"</font>";
				}
            }

            if (typeof printer.isAutoCutSupported != "undefined")
            {
                if (printer.isAutoCutSupported)
                    info = info + "<i><u><br/>"+objects.autocut+"</u></i>";
                else
                    info = info + "<font size='6'><br/>"+objects.no_autocut+"</font>";
            }

            info = info + "</font>";

            // when printing put info into object with name "Text"
            record.setTextMarkup("Text", info);
        }

        // finally print label with default printing parameters
        dymo.label.framework.printLabel(printerName, "", labelXml, labelSetBuilder);
		add_log('Success: Print printer information on '+printerName,'ok');
		});
    }

	// Debug log
	function add_log(message,type) {
		var ul = jQuery('#dymo-debug-log-output');
		jQuery(ul).prepend('<li class="'+type+'">'+message+'</li>');
	}

	// Clear debug logs
	function clear_log() {
		jQuery('#dymo-debug-log-output li').remove();
	}

    // Init all settings scripts
    function InitCheck()
    {

		if(!jQuery('body').hasClass('wc-dymo-framework-error')) {

			var labelCompanyText = document.getElementById('woocommerce_dymo_company_name');
			var labelExtraText = document.getElementById('woocommerce_dymo_company_extra');
			var printButton = document.getElementById('printButton');
			var updateTableButton = document.getElementById('updateTableButton');
			var printersSelect = document.getElementById('printersSelect');
			var clearLogs = document.getElementById('debugClear');

			// Load preview on startup
			setupDefaultLayout();

			// load printers list on startup
			updateTableButton.onclick = updatePrintersTable;
			updatePrintersTable();

			// Print printers information on click
			printButton.onclick = function(){
				print(printersSelect.value);
			}

			// Print printers information on click
			clearLogs.onclick = function(){
				clear_log();
			}

			setTimeout(function() {
				_labelData.COMPANY = labelCompanyText.value;
				applyDataToLabel(_label, _labelData);
				_labelData.EXTRA = labelExtraText.value;
				applyDataToLabel(_label, _labelData);
				updatePreview();
			},800);

			try
            {
                var result = dymo.label.framework.checkEnvironment();
                add_log(objects.isBrowserSupported + ' '+ result.isBrowserSupported,'ok');
                add_log(objects.isFrameworkInstalled + ' '+ result.isFrameworkInstalled,'ok');
				if(dymo.label.framework.init){
					add_log(objects.isWebServicePresent + ' '+ result.isWebServicePresent,'ok');
				}
				if(result.errorDetails!="")
					add_log(objects.errorDetails + ' '+ result.errorDetails,'error');

				add_log(objects.pluginVersion,'ok');
				add_log(objects.frameworkVersion + ' '+dymo.label.framework.VERSION,'ok');
				if(objects.debugmode==1) {
					add_log(objects.debugmodeTxt);
				}
				add_log(objects.environmentCheck,'bold');

            }
            catch(e)
            {
                add_log(e.message || e);
            }


			// updates address on the label when user types in textarea field
			labelCompanyText.onchange = function()
			{
				if (!_label)
				{
					alert('Load label before entering text');
					return;
				}
				// set labelData
				_labelData.COMPANY = labelCompanyText.value+ '\u0020';
				applyDataToLabel(_label, _labelData);
				updatePreview();
			}

			// updates address on the label when user types in textarea field
			labelExtraText.onchange = function()
			{
				if (!_label)
				{
					alert('Load label before entering text');
					return;
				}
				// set labelData
				_labelData.EXTRA = '\u0020'+labelExtraText.value;
				applyDataToLabel(_label, _labelData);
				updatePreview();
			}


		}
    };

	// Init Shim needed for DYMO Javascript Framework
	function frameworkInitShim() {
		window.addEventListener('error', function (evt) {

		evt.preventDefault();
		if(evt.message=='Uncaught Error: DYMO Label Framework Plugin is not installed') {
			console.log(evt.message);
			add_log(evt.message,'error');
			add_log(objects.clearCache,'error');
			add_log(objects.noFramework,'error');
		}

		});

		try {
			dymo.label.framework.init(InitCheck);
		} catch (ex) {
			console.log(ex);

			if(ex=='Error: DYMO Label Framework service discovery is in progress.') {
				ex = objects.ServiceDiscovery
			}
			add_log(objects.clearCache,'error');
			add_log(ex,'error');

		}
	}

	//setTimeout(function() {frameworkInitShim();},200);

	// register onload event
    if (window.addEventListener)
        window.addEventListener("load", frameworkInitShim, false);
    else if (window.attachEvent)
        window.attachEvent("onload", frameworkInitShim);
    else
        window.onload = frameworkInitShim;

} ());

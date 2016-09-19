var pwstPowerStaging = (function(module, $) {

	var _index = 0;

	module.addCustomField = function() {
		var template = 
			"<tr id='pwst_custom_field_"+_index+"'>"+
				"<th>"+
					"<label for='pswt_custom_field_old_value_"+_index+"'>Value</label>"+
				"</td>"+
				"<td>"+
					"<input type='text' id='pswt_custom_field_old_value_"+_index+"' name='pwst_custom_field_old_value[]' class='regular-text' required>"+
				"</td>"+
				"<th>"+
					"<label for='pswt_custom_field_new_value_"+_index+"'>Replace to</label>"+
				"</td>"+
				"<td>"+
					"<input type='text' id='pswt_custom_field_new_value_"+_index+"' name='pwst_custom_field_new_value[]' class='regular-text' required>"+
				"</td>"+
				"<td>"+
					"<button onclick='return false;' class='button pwst-button-warning' disabled>Disable</button>"+
				"</td>"+
				"<td>"+
					"<button onclick='pwstPowerStaging.removeCustomField("+_index+"); return false;' class='button pwst-button-danger'>Remove</button>"+
				"</td>"+
			"</tr>";

		_index++;

		$("#pwst_custom_fields").append($(template));
	};

	module.removeCustomField = function(index) {
		if (confirm("Confirm you want to remove this field?")) {
			$("#pwst_custom_field_"+index).remove();
		}
	};

	return module;

})(pwstPowerStaging || {}, jQuery || $);
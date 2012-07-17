$(function(){
	$("#AddField").click(function(){
		$('#FieldSetting').modal();
	})


	$('#FieldSetting').live('submit', function() {
		// отправка формы
		$(this).ajaxSubmit({
			url:       '/admin/ajax/validatefield',
			type:      'post',
			dataType:  'json',
			beforeSubmit: function (formData, jqForm, options) {
				$(jqForm).find('div.error').removeClass('error').end().find('.help-block').empty();
			},
			success:  function (data, statusText, xhr, $form)  {
				if ( data.success == false ){
					$.each( data.errors , function(field, error) {
						$('#FieldSetting')
							.find('[name="ProductField\\['+field+'\\]"]')
							.closest('div.control-group').addClass("error")
							.find('.help-block')
							.text( ""+error );
					});
				} else if ( data.success == true ) {
					var field = '<tr>';
					field += '<td></td>';
					field += '<td>';
					field += $($form).find('[name="ProductField\\[FieldType\\]"] option:selected').text();
					field += '</td>';
					field += '<td>';
					field += $($form).find('[name="ProductField\\[Name\\]"]').val();
					field += '</td>';
					field += '<td></td>';
					field += '<td></td>';
					field += '<td><button class="close">&times;</button></td>';
					field += '</tr>';
					$("#ProductField").find('tbody').html(field);
					$('#FieldSetting').modal('hide');
				}
			}
		});
		return false;
	})

})
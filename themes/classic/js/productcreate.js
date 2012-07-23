$(function(){
	$("#AddField").click(function(){
		$('#FieldSetting').modal();
	});

	$('span.close').live('click', function() {
		$(this).closest('tr').remove();
	});

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
					var i = "new_"+$("#ProductField").find('tbody tr').length;

					var field = '<tr>';
					field += '<td></td>';

					field += '<td>';
					var FieldType = $($form).find('[name="ProductField\\[FieldType\\]"] option:selected').val();
					field += '<input type="hidden" name="Products[ProductField]['+i+'][FieldType]" value="'+FieldType+'"/>';
					field += $($form).find('[name="ProductField\\[FieldType\\]"] option:selected').text();
					field += '</td>';

					field += '<td>';
					var Name = $($form).find('[name="ProductField\\[Name\\]"]').val();
					var Alias = $($form).find('[name="ProductField\\[Alias\\]"]').val();
					field += '<input type="hidden" name="Products[ProductField]['+i+'][Name]" value="'+Name+'"/>';
					field += '<input type="hidden" name="Products[ProductField]['+i+'][Alias]" value="'+Alias+'"/>';
					field += Name;
					field += ' ('+Alias+')';
					field += '</td>';

					var IsMandatory = $($form).find('[name="ProductField\\[IsMandatory\\]"]:checked').val();
					field += '<td>';
					field += '<input type="hidden" name="Products[ProductField]['+i+'][IsMandatory]" value="'+ (IsMandatory ? 1 : 0) +'"/>';
					field += '<i class="'+ (IsMandatory ? "icon-plus" : "icon-minus") +'"></i></td>';

					var IsFilter = $($form).find('[name="ProductField\\[IsFilter\\]"]:checked').val();
					field += '<td>';
					field += '<input type="hidden" name="Products[ProductField]['+i+'][IsFilter]" value="'+ (IsFilter ? 1 : 0) +'"/>';
					field += '<i class="'+ (IsFilter ? "icon-plus" : "icon-minus") +'"></i></td>';

					field += '<td><span class="close">&times;</span></td>';
					field += '</tr>';
					$("#ProductField").find('tbody').append(field);
					$('#FieldSetting').modal('hide');
				}
			}
		});
		return false;
	});


})
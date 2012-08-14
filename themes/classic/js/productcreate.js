$(function(){
	$("#AddField1").click(function(){
		$('#FieldSetting').modal();
	});

	$('span.close').live('click', function() {
		$(this).closest('tr').remove();
	});

	$('body').on('click','span.icon-pencil',function(){
		$.ajax({
			'type':"POST",
			'data':{'action':"FieldForm"},
			'onclick':'$("#jobDialog").dialog("open\");return false;',
			'url':'',
			'cache':false,
			'success':function(html){
				jQuery("#jobDialog").html(html)
			}
		});

		return false;
	});

	$('body').on('submit','#FieldForm', function() {

		var intuts = $(this).find('select,input[type="text"],input[type="checkbox"]:checked');
		var table = $('#ProductField > tbody');
		var col = table.find("tr").length+1;
		var td = $('<td></td>');
		var tdID = td.clone();
		var tdFieldType = $('<td></td>');
		var tdNameAlias = $('<td></td>');
		var iEl = $('<i></i>').addClass('icon-minus');
		var tdIsMandatory = $('<td></td>').append(iEl.clone());
		var tdIsFilter = $('<td></td>').append(iEl.clone());
		var tdEdit = $('<td></td>').append($('<span></span>').attr({'class':'icon-pencil pointer','title':'Редактировать'}));
		var tr = $('<tr></tr>').append(tdID);
		$.each(intuts,function(i,source){
			var nameEl = $(source).attr('name').match(/\[(.*)\]/i);

			if ( nameEl !== null ) {

				var name = "Products[ProductField]["+col+"]["+nameEl[1]+"]";
				var input = $("<input/>").attr({'type':"hidden","name":name,"value":$(source).val()});
				$(tdID).append(input);

				console.log(nameEl[1]);
				switch (nameEl[1]){
					case "FieldType":
						tdFieldType.text($(source).find("option:selected").text());
					break;
					case "Name":
						tdNameAlias.text($(source).val());
					break;
					case "IsMandatory":
						tdIsMandatory.find('i').removeClass('icon-minus').addClass('icon-plus');
					break;
					case "IsFilter":
						tdIsFilter.find('i').removeClass('icon-minus').addClass('icon-plus');
					break;
				}
			}

		});
		tr.append(tdFieldType,tdNameAlias,tdIsMandatory,tdIsFilter,tdEdit,td);
		table.append(tr);

		$("#jobDialog").dialog("destroy");
		return false;
	});


});
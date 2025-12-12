$(document).ready(function () {
    $('#add_role_menu').click(function () {
        var menuId = $('#menu_id').val();
        var homeRouter = $("#home_router").val();
        resetWarning();

        if (menuId == '') {
            $('#menu_id').addClass('has-warning');           
            $('#menu_id').siblings('span:first').text('Menu is reqired');        
            return;
        }

        $.ajax({
            global: true,
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "/roles/" + $('#role_id').val() + "/functions/add",
            data: {
                menu_id: menuId,
                home_router: homeRouter,
            },
            beforeSend: function () {
                $('#add_role_menu').attr("disabled", "disabled");
            },
            success: function (response) {
                $("#nonedata").remove();
                if (response.hasOwnProperty('result')) {
                    var roleMenu = response.result;
                    if (roleMenu.home_router)
                        $('#tableRoleMenu tbody').append(
                            $('<tr>').append(
                                $('<td>').text(roleMenu.menu.name)
                            ).append(
                                $('<td>').attr('align', 'center').append($('<span>').addClass('label bg-maroon flat').text('Home page'))
                            ).append($('<td>').attr('align', 'center').append($('<a>').addClass('delete-item').attr('id', roleMenu.id).append(' <i class="fa fa-trash-o btn"></i>')))
                        );
                    else
                        $('#tableRoleMenu tbody').append(
                            $('<tr>').append(
                                $('<td>').text(roleMenu.menu.name)
                            ).append(
                                $('<td>').attr('align', 'center').append(
                                    $('<span>').addClass('label bg-olive flat').text('Normal page')
                                )
                            ).append($('<td>').attr('align', 'center').append(
                                $('<a>').addClass('delete-item').attr('id', roleMenu.id).append(' <i class="fa fa-trash-o btn"></i>')
                            )
                            )
                        );                    
                }
                $('#' + roleMenu.id).click(function(){
                    removeFunction($(this).attr('id'));
                });
            },
            error: function (response) {
                switch (response.status) {
                    case 400:
                        var responseJSON = response.responseJSON;
                        if (responseJSON.hasOwnProperty('result')) {
                            var result = responseJSON.result;
                            if (result.hasOwnProperty('menu_id')) {
                                var textHelpBlock = '';
                                result.menu_id.forEach(element => {
                                    if (textHelpBlock == '')
                                        textHelpBlock = element;
                                    else
                                        textHelpBlock = textHelpBlock + '<br/>' + element;
                                });                                
                                $('#menu_id').addClass('has-warning');
                                $('#menu_id').siblings('span:first').html(textHelpBlock);
                            }                            
                        }
                        break;
                    default:
                        break;
                }
            },
            complete: function () {
                $('#add_role_menu').removeAttr('disabled');
            }
        });
    })

    $('.delete-item').click(function () {
		removeFunction($(this).attr('id'));
    });
    
    function removeFunction(id) {
        var linkDelete = $('#' + id);
        resetWarning();
        
		$.ajax({
			global: true,
			type: "DELETE",
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			url: "/functions/delete/" + id,
			data: {
			},
			beforeSend: function () {
				linkDelete.attr("disabled", "disabled");
			},
			success: function (response) {
				linkDelete.parent().parent("tr:first").remove();
				if ($('#tableRoleClient tr').length <= 1) {
					$('#tableRoleClient tbody').append('<tr id="nonedata"><td colspan="3" style="text-align:center; font-weight:300"><span>None data</span></td></tr>');
				};
			},
			error: function (response) {
				var responseJSON = response.responseJSON;
				if (responseJSON.hasOwnProperty('message')) {
					$('#message').text(responseJSON.message);
				}				
			},
			complete: function () {
				linkDelete.removeAttr('disabled');
			}
		});
    }
    
    function resetWarning() {
        $('#message').text(null);
        $('.help-block').text(null);
        $('#role_id').removeClass('has-warning');
    }
});
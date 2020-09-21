$(document).ready(function(){
        $('.button_new').click(function(){
            var name_number = $('._new_rows').size();
        	$.ajax({
			type:'post',
			url:'/application/testajax',
			data:{name_number:name_number},
			beforeSend:function(){},
			success:function(results)
			{
			 //alert(results);
             var x = $('#contacts',results).html();
                $('#new_contact').append(x);
             //alert(x);
				//$('fieldset#fld_reasons_reasons table#decision_table tbody').append(results);
			},
			complete:function(){
				//do the complete thing
			}
		})
    }) 
                         
});

var app = {
    init:function(){},
    name_remove:function(name_number){
        //alert(name_number);
        id_data = name_number.split('_');
        var OK = confirm('The selected row will be removed , do you want to continue ?');
        if(OK){
            $('#row_' + id_data[1]).fadeOut('slow',function(){
                $(this).remove();
                app.resert_name_numbers();
            })
        }else{}
    },
    contact_row:function(){
            var name_number = $('._new_rows').size();
        	$.ajax({
			type:'post',
			url:'/contacts/ajaxcontact',
			data:{name_number:name_number},
			beforeSend:function(){},
			success:function(results)
			{
			 //alert(results);
             var x = $('#contacts',results).html();
                $('#_client_contact').append(x);
                $('._hide').addClass('_show unhide').removeClass('_hide');
                $('.unhide').removeClass('_show').fadeIn('slow');
             //alert(x);
				//$('fieldset#fld_reasons_reasons table#decision_table tbody').append(results);
			},
			complete:function(){
				//do the complete thing
			}
    })
},
    new_contact_row:function(){
            var name_number = $('._new_rows').size();
        	$.ajax({
			type:'post',
			url:'/contacts/ajaxclientcontact',
			data:{name_number:name_number},
			beforeSend:function(){},
			success:function(results)
			{
			 //alert(results);
             var x = $('#contacts',results).html();
                $('#new_contact').append(x);
                app.prepare_autosugest(name_number);
             //alert(x);
				//$('fieldset#fld_reasons_reasons table#decision_table tbody').append(results);
			},
			complete:function(){
				//do the complete thing
			}
		})
    },
    prepare_autosugest:function(name_number){
        name_number = name_number +1;
        $.fn.autosugguest({  
               className: 'ausu-suggest' + name_number,
              methodType: 'POST',
                minChars: 4,
                  rtnIDs: true,
                dataFile: '/contacts/clients'
        });
        
        $('.ausu-suggest' + name_number).css('position','relative').css('float','left');
    ;
    },
        resert_name_numbers:function(){
        $("._numbers").each(function(i){
            $(this).html(i + 1);
        });
    }
}
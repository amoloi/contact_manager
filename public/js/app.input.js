$(document).ready(function(){
        $('input[type=text]').each(function(){
            if($(this).val() == ''){
                $(this).val($(this).attr('help'));
            }
        });
        $('input[type=text]').focus(function(){
                if($(this).val() == $(this).attr('help')){
                $(this).val('');
            }
        });
        $('input[type=text]').blur(function(){
                if($(this).val() == ''){
                $(this).val($(this).attr('help'));
            }else{
                
            }
        })
})
$(document).ready(function(){ 
    if(user_active){        
        $("#sing_in").hide();
        $("#login").hide();
        $("#logout").show();
        $("#update_user").show();
    } else{
        $("#sing_in").show();
        $("#login").show();
        $("#logout").hide();
        $("#update_user").hide();
    }
}); 
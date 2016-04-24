var garbage = {
	preview: function(id) {
		$.get("/event/get?id="+id, function(data) {
			data = JSON.parse(data);
			$("#previewTitle").html(data.maintitle);
			$("#previewText").html('<p class="bg-danger" id="errors" style="padding: 10px; display: none;"></p><div class="row">\
					    	<div class="col-xs-6">Где?</div>\
					    	<div class="col-xs-6">г. '+data.city+', ул '+data.street+', дом '+data.house+'</div>\
					    </div><div class="row">\
					    	<div class="col-xs-6">Когда?</div>\
					    	<div class="col-xs-6">'+data.cleaningtime+'</div>\
					    </div>');
			$("#previewFooter").html('<button type="button" class="btn btn-success btn-lg btn-block" onclick="garbage.visit('+id+');">Я ПОМОГУ!</button>');
			$("#preview").modal("show");
		});
	},
	visit: function(id){
		$.post("/event/visit", {gid: id}, function(data) {
			var result = $.parseJSON(data);
    var error_msg = result.error_msg;
    if(result.response) {
    	window.location.href = "/"
    } else if(error_msg) {
     $('#errors').show().html(error_msg);
    }
		});
	}
};
var users = {
	showAuthForm: function() {
			$("#regModal").modal('hide');
			$("#authModal").modal("show");
	},
	showRegForm: function() {
			$("#authModal").modal('hide');
			$("#regModal").modal("show");
	},
 auth: function() {
  var login = $('#auth_login');
  var password = $('#auth_password');

  if(!isLogin(login.val())) {
   login.focus();
  } else if(!isPassword(password.val())) {
   password.focus();
  } else {

   $.post('/users/auth', {
    login: login.val(),
    password: password.val(),
   }, function(data) {
    var result = $.parseJSON(data);
    var error_msg = result.error_msg;
    if(result.response) {
    	window.location.href = "/"
    } else if(error_msg) {
     $('#authErrors').show().html(error_msg);
    }
   });
  }
 },
 reg: function() {
  var login = $('#reg_login');
  var password1 = $('#password1');
  var password2 = $('#password2');
  var email = $('#reg_email');

  $('#regErrors').hide();
  if(!isEmail(email.val())) {
   email.focus();
   var error_msg = 'Введите корректный почтовый адрес.';
   $('#regErrors').show().html(error_msg);
   return false;

  } else if(!isLogin(login.val())) {
   login.focus();

   var error_msg = 'Логин может содержать только латинские символы, некоторые знаки или цифры и не превышать 2-20 символов.';
   $('#regErrors').show().html(error_msg);
   return false;
  } else if(!isPassword(password1.val())) {
   password1.focus();

   var error_msg = 'Пароль может содержать только латинские символы, некоторые знаки или цифры и не превышать 6-30 символов.';
   $('#regErrors').show().html(error_msg);
   return false;

  } else if(password1.val() != password2.val()) {
   password1.focus();

   var error_msg = 'Введите корректный повтор пароля.';
   $('#regErrors').show().html(error_msg);
   return false;
  }
  $.post('/users/reg', {
   login: login.val(),
   password1: password1.val(),
   password2: password2.val(),
   email: email.val(),
  }, function(data) {
   var result = $.parseJSON(data);
   var error_msg = result.error_msg;

   if(result.response) {
    window.location.href = "/"
   } else if(error_msg) {
    if(result.field_name) {
     $('#'+result.field_name).focus();
    }

    $('#regErrors').show().html(error_msg);
   }
  });
 }

}
function declOfNum(number, titles) {
 cases = [2, 0, 1, 1, 1, 2];

 return titles[(number%100>4 && number%100<20)? 2 : cases[(number%10<5)?number%10:5]];
}

function isLogin(login) {
 return login.match(/^([@a-zA-Z0-9\-\.\_\*]){2,30}$/gi);
}

function isPassword(password) {
 return password.match(/^([@a-zA-Z0-9\-\.\_\*]){6,32}$/gi);
}

function isEmail(email) {
 return email.match(/^[a-zA-Z0-9\.\-\_]+@([a-zA-Z0-9\-\_]+\.)+[a-zA-Z]{2,6}$/gi);
}

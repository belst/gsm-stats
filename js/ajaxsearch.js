/**
* @Author: belst
* @Date:   20-09-2016
* @Email:  gsm@bel.st
* @Last modified by:   belst
* @Last modified time: 20-09-2016
* @License: BSD3
*/

var Users = {
	init: function(config) {
		this.config = config;

		this.setupTemplates();
		this.bindEvents();

		$.ajaxSetup({
			url: 'index.php',
			type: 'POST',
			dataType: 'json'
		});

	$('button').remove(); // not needed if js is enabled
	},

	bindEvents: function() {
		this.config.searchBox.on('keyup', this.searchUsers);;
		this.config.userList.on('click', 'tr', this.displayUserInfo);;
	},

	setupTemplates: function() {
		this.config.userListTemplate = Handlebars.compile(this.config.userListTemplate);;
		this.config.userInfoTemplate = Handlebars.compile(this.config.userInfoTemplate);;

		Handlebars.registerHelper('kd', function(user) {
			return (!user.kd) ? Math.round(user.kd * 100) / 100 : (user.kills != 0) ? "Infinity" : 0;
		});
		Handlebars.registerHelper('hsr', function(user) {
			return (!user.hsr) ? Math.round(user.hsr * 100) / 100 : 0;
		});
	},

	searchUsers: function() {
		var self = Users;
		var delay = (function(){
			var timer = 0;
			return function(callback, ms){
				clearTimeout(timer);
				timer = setTimeout(callback, ms);
			};
		})();
		delay(function(){
			$.ajax({
				data: self.config.form.serialize(),
				success: function(results) {
					self.config.userList.empty();

					if (results[0]) {
						self.config.userList.append(self.config.userListTemplate(results));
					} else {
						self.config.userList.append('<tr><td colspan="6">Nothing found</td></tr>');
					}
				}
			});


		}, self.config.delay);;
	},

	displayUserInfo: function(e) {
		var self = Users;

		$('#myModal').modal('hide');

		$.ajax({
			data: { id: $(this).data('user_id') },
			success: function(results) {
				if(results[0]) {
					self.config.userInfo.html(self.config.userInfoTemplate(results));
					$('#myModal').modal('show');
				} else {
					self.config.userInfo.html('<li>Nothing returned</li>');
					$('#myModal').modal('show');
				}
			}
		});

		e.preventDefault();
	}
};

Users.init({
	searchBox: $('input[name="q"]'),
	form: $('form.form-search'),
	userListTemplate: $('#user_list_template').html(),
	userList: $('tbody.userlist'),
	userInfoTemplate: $('#user_info_template').html(),
	userInfo: $('ul#userinfo'),
	delay: 300 //Delay for ajax request after keyup to wait till user stops typing
});

/*
 * Login v1.0 | Sam Mateosian & Max Felker
*/

var Login = Class.create({
	
	initialize: function(config) {
			
		var $this = this;
		
		// unpack config
		this.config = config;
		this.id = config.id;
		this.container = $(this.id);
		this.success_page = config.success_page;
		
		// create jive form
		this.form = new JiveForm({
			container_id:config.id,
			action:config.action
		});
			
		// set custom form callbacks
		this.form.success(this.post);
		//this.form.failure(this.failure);
	},
	
	// posts data to a controller on successful form validation
	post: function(){
		
		var $this = this;
		
		
		
		// remove all error blurbs
		$('.blurb-error').remove();
		
		$j.messenger.clear();
		
		// set data to send
		var data = this.serializeArray();
		
		// send it to controller
		$.post(
			this.config.action, // pulls from form config 
			data,
			function(data){
				
				// on success
				if(data.success) {
					
					// generate success msg
					/*$j.msg({
						type:'success',
						content:"<p><b>Success!</b></p>" // should come from server
					});*/
					
					// redirect me to the success rout
					//$j.nav.go(data.redirect.route, data.redirect.url);
					
					$this.container.hide();
					
					if($this.success_page != undefined){
					
						window.location = $this.success_page;
						
					}else{
						
						window.location = data.url;		
					}
					
					
					
				} else {
					
					//alert('here');
					
					//alert($this.container);
					
					$this.container.effect("shake", { times:2 }, 50);
					
					$this.container.find('#password').attr('value', '');
					
					$this.container.find('#password').focus();
					
					// sorry bro, something went wrong
					/*$j.msg({
						type:'failure',
						sticky:1,
						content:"<p><b>Oops!</b> " + data.error + "</p>"
					});*/
						
				}
				
			}, 'json'); // end post call
	
	}, // end post function
	
	
});

// if the login is expired and there was an ajax request, reload the current view to get a propper login redirect
$(document).ajaxError(function (event, jqXHR) {
	if (403 === jqXHR.status) {
		window.location.reload();
	}
});


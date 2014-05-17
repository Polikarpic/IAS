jQuery
( 
	function($)
		{
			$('tbody tr[data-href]').addClass('clickable').click
				( 
					function()
					{ 
						window.location = $(this).attr('data-href');
					}
				)
		}
);

  

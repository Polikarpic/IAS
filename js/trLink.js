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
				).find('img').hover
				(					
					function()
					{
						$(this).parents('tr').unbind('click');
					},
					function() 
					{
						$(this).parents('tr').click
						( 
							function()
							{
								window.location = $(this).attr('data-href');
							}
						);
					}
				),
				$('tbody tr[data-href]').addClass('clickable').click
				( 
					function()
					{ 
						window.location = $(this).attr('data-href');
					}
				).find('input').hover
				(					
					function()
					{
						$(this).parents('tr').unbind('click');
					},
					function() 
					{
						$(this).parents('tr').click
						( 
							function()
							{
								window.location = $(this).attr('data-href');
							}
						);
					}
				);
		}
);

  

$(document).ready(function(){
$('span#cartCount').html('').hide();
	$.ajax({
	    	type    : 'GET',
	    	cache	: false,
	        url     : 'index.php?action=ajaxcall&func=checkcart',
	        dataType: 'json',
	        
	        success : function(data) 
	        {
	        	if(data.counter > 0)
	        	{
		      		$('span#cartCount').html('').show();
		      		$('span#cartCount').html(data.counter);
		      	}
		      	if(data.MSG)
				{
					$('div.SYSMSG').html(data.MSG).show();
				}
	        },
	       	error: function(jqXHR,textStatus,errorThrown ) 
			{ 
				$('div.SYSMSG').html('').hide();
				$('div.SYSERR').html(textStatus+' '+jqXHR.responseText).show();
			} 					
	});

$('a#addToCart.button').click(function(){
	var id = $(this).attr('data-kvr-item-id');
	var amount = $('#amount-'+id).val();
        $.ajax({
        	type    : 'GET',
        	cache	: false,
            url     : 'index.php?action=ajaxcall&func=updatecart&item='+id+'&amount='+amount,
            dataType: 'json',
            data    : {
            current_value: $('span#cartCount').html() //set value here
            },
            success : function(data) 
            {
		      	$('span#cartCount').html('').show();
		      	$('span#cartCount').html(data.counter);
		      	if(data.MSG)
				{
					$('div.SYSMSG').html(data.MSG).show();
				}

            },
           	error: function(jqXHR,textStatus,errorThrown ) 
			{ 
				$('div.SYSMSG').html('').hide();
				$('div.SYSERR').html(textStatus+' '+jqXHR.responseText).show();
			} 					
        });
    });

$('div#removefromcart').click(function(){
	var id = $(this).attr('data-kvr-item-id');
        $.ajax({
        	type    : 'GET',
        	cache	: false,
            url     : 'index.php?action=ajaxcall&func=removefromcart&item='+id,
            dataType: 'json',
            data    : {
            current_value: $('span#cartCount').html() //set value here
            },
            success : function(data) 
            {
		      	$('tr#row-'+id).hide();
		      	$('th#total_price.num').html(data.total_cart);
		      	if(data.new_value > 0)
			    {
			      	$('span#cartCount').html(data.counter);
		      	} 
		      	else
		      	{
		      		$('span#cartCount').html(data.counter).hide();
		      	}
		      	if(data.MSG)
				{
					$('div.SYSMSG').html(data.MSG).show();
				}
            },
           	error: function(jqXHR,textStatus,errorThrown ) 
			{ 
				$('div.SYSMSG').html('').hide();
				$('div.SYSERR').html(textStatus+' '+jqXHR.responseText).show();
			} 					
        });
    });
});


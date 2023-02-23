$(document).ready(function(){
$('div.SYSERR').html('').hide();
$('div.SYSMSG').html('').hide();
$("#rating").on("click", "input:radio", function(){
	var rating = $(this).attr('value');
	var item = $(this).attr('data-kvr-item-value');
	$.ajax({
			type	: 'GET',
			cache 	: false,
			url		: 'index.php?action=ajaxcall&func=rating&itemID='+item+'&rateValue='+rating,
			dataType: 'json',
			success	: function(data)
			{
				$('div.SYSERR').html('').hide();
				if(data)
				{
					if(data.message)
					{
						$('div.SYSMSG').html(data.message).show();
					}
					$.each(data.items, function(i)
					{
						var item = data.items[i];
						console.log(item.target);
						$(item.target).html(item.content);
					});
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

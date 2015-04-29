$jKyash = jQuery.noConflict();
var old_postcode = '';
var errorMessage = 'Due to some unexpected errors, this is not available at the moment. We are working on fixing it.';
function pullNearByShops(postcode,url,loader)
{
	if(postcode.length == 0)
	{
		alert('Enter your post code to retrieve the shops');
	}
	else
	{
		if(old_postcode != postcode)
		{
			$jKyash('#see_nearby_shops_container2').show();
			var loader = '<img src="'+loader+'" title="Processing..." alt="Processing...">';
			$jKyash('#see_nearby_shops_container2').html(loader);
			$jKyash.get(url+'?postcode='+postcode, function(output){
				$jKyash('#see_nearby_shops_container2').html(output);
			});
			
			$jKyash.ajax({
				 url: url+'?postcode='+postcode, 
				 success: function(output, textStatus, xhr){
					 if(xhr.status == 400 || xhr.status == 200)
					 {
						$jKyash('#see_nearby_shops_container2').html(output);
					 }
					 else
					 {
						 $jKyash('#see_nearby_shops_container2').html(errorMessage);
					 }
					 old_postcode = postcode;
				 }
			});
			
		}
	}
}

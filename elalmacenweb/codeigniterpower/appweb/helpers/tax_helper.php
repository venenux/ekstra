<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// --------------------------------------------------------------------

/**
 * @returns calculated incremented tax respect the price as first argument in form 0.00
 * @arg price number
 * @arg percentage number, default 10
 */
function taxadd($price = NULL, $percentage = 10 )
{
	if (!$price)
	{
		return format_price(0);
	}
	
	if (!$percentage)
	{
		return format_price(0);
	}
	
	return format_price($price * (($percentage / 100) + 1));
}

// --------------------------------------------------------------------

/**
 * @returns base calculated tax respect the price as first argument in form 0.00
 * @arg price number
 * @arg percentage number, default 10%
 */
function taxget($price = NULL, $percentage = 10)
{
	if (!$price)
	{
		return format_price(0);
	}
	
	if (!$percentage)
	{
		return format_price(0);
	}
	
	return format_price($price * ($percentage / 100));
}

// --------------------------------------------------------------------

function format_price($price = 0)
{
	return number_format($price, 2, '.', '');
}

// --------------------------------------------------------------------

/* End of file tax_helper.php */
/* Location: ./application/helpers/tax_helper.php */
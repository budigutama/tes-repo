<?php
/*

	Paypal Button generator by JC21  		jc@jc21.com				www.jc21.com
	
	VERSION 1.1
		
	Creates a Paypal button for Checkout, Donate or Pay now circumstances. Supports Cart, more than 1 item. Supports Tax, postage and handling,
	css styles, form targets etc. Fully customizable.

	
	Paypal button details:  https://www.paypal.com/au/cgi-bin/webscr?cmd=p/pdn/howto_checkout-outside#methodone


	USAGE:
	
		require_once('paypal.inc.php');		//require the class include file

		$button = new PayPalButton;														//initiate the class instance
		$button->accountemail = 'jason@almost-anything.com.au';							//the account that is registered with paypal where money will be sent to
		$button->custom = 'my custom passthrough variable'; 							//a custom string that gets passed through paypals pages, back to your IPN page and Return URL as $_POST['custom'] . useful for database id's or invoice numbers. WARNING: does have a max string limit, don't go over 150 chars to be safe
		$button->currencycode = 'AUD';													//currency code
		$button->target = '_blank';														//Frame Name, usually '_blank','_self','_top' . Comment out to use current frame.
		$button->class = 'paypalbutton';												//CSS class to apply to the button. Comes in very handy
		$button->width = '150';															//button width in pixels. Will apply am Inline CSS Style to the button. Comment if not needed.
		$button->image = 'http://www.jc21.com.au/paypal/logo.jpg';						//image 150px x 50px that can be displayed on your paypal pages.
		$button->buttonimage = '/paypal/purchase.jpg';									//img to use for this button
		$button->buttontext = 'I agree, proceed to Payment';							//text to use if image not found or not specified
		$button->askforaddress = false;													//wether to ask for mailing address or not
		$button->return_url = 'http://www.aussiehorsebrokers.com.au/paypal.php';		//url of the page users are sent to after successful payment
		$button->ipn_url = 'http://www.aussiehorsebrokers.com.au/paypal/ipn.php';		//url of the IPN page (this overrides account settings, IF IPN has been setup at all.
		$button->cancel_url = 'http://www.aussiehorsebrokers.com.au/paypal_cancel.php'; //url of the page users are sent to if they cancel through the paypal process

		//ITEMS
		//Paypal buttons are different when you're selling 1 item and anything more than 1 item. My class takes care of this for you.
		//Syntax: $button->AddItem(item_name,quantity,price,item_code,shipping,shipping2,handling,tax);
		//Here are a few examples:
		$button->AddItem('Item Name','1','100.00','wsc001');							//1 quantity, no shipping, no handling, default tax.
		$button->AddItem('Item Name','1','100.00','wsc001','','','','0.00');			//1 quantity, no shipping, no handling, NO TAX
		$button->AddItem('Item Name','3','100.00','wsc001','10.00');					//3 quantities, $10.00 shipping, no handling, default tax.

		
		    * a3 - amount to be invoiced each recurrence
			* t3 - time period (D=days, W=weeks, M=months, Y=years)
			* p3 - number of time periods between each recurrence

		
		$button->OutputButton();														//output the button!
	
	
	-------------------------------------------------------------------------------
	 UPDATE LOG:
	-------------------------------------------------------------------------------
	30-11-2005		Version 1.1 Release
	29-11-2005		Added: Options for Purchase Items
	29-11-2005		Added: Paypal Subscriptions Support
	12-10-2005		Version 1.0 Release



*/

class PayPalButton {
	
	var $accountemail;      // email of paypal seller account 
	var $currencycode;      // currencycode USD or AUD etc
	var $amount;			// total amount. not sure wether to calculate this myself yet.
	var $custom;			// custom passthrough field
	var $items;		 	 	// array of items
	var $subscriptions;		// array of subscriptions, should only be 1 item there.
	var $target;			// window target: _blank, _top, _self
	var $image;				// paypal image, 150 x 50px
	var $buttonimage;		// button image.
	var $buttontext;		// button text.
	var $askforaddress;		// true or false
	var $ipn_url;			// IPN url
	var $return_url;		// after successful payment url
	var $cancel_url;		// after cancel payment url
	var $class;				// for styling
	var $width;				// in pixels.
	var $askfornote;		// ask for note. applies more to subscriptions.
	
	
	function PayPalButton() {
		//set up some defaults
		$this->accountemail = '';
		$this->currencycode = 'AUD';
		$this->amount = '0.00';
		$this->custom = '';
		$this->items = array();
		$this->subscriptions = array();
		$this->target = '';
		$this->target_text = '';
		$this->image = '';
		$this->buttonimage = '';
		$this->buttontext = 'Purchase';
		$this->askforaddress = true;
		$this->ipn_url = '';
		$this->return_url = '';
		$this->cancel_url = '';
		$this->class = '';
		$this->width = '';
		$this->askfornote = true;
	}
	
	function AddItem($item_name,$quantity,$price,$item_no='',$shipping='',$shipping2='',$handling='',$tax='',$firstfieldname='',$firstfieldoptions='',$secondfieldname='',$secondfieldoptions='') {
		//add item
		$this->items[] = array('name'=>$item_name,'qty'=>$quantity,'price'=>$price,'item_no'=>$item_no,'shipping'=>$shipping,'shipping2'=>$shipping2,'handling'=>$handling,'tax'=>$tax,'firstfieldname'=>$firstfieldname,'firstfieldoptions'=>$firstfieldoptions,'secondfieldname'=>$secondfieldname,'secondfieldoptions'=>$secondfieldoptions);
		//reset subscription array incase of user error
		$this->subscriptions = array();
	}
	
	function AddSubscription($item_name,$price,$item_no='',$interval='1',$time_period='M',$tax='') {
		//add item
		$this->subscriptions[] = array('name'=>$item_name,'price'=>$price,'item_no'=>$item_no,'interval'=>$interval,'time_period'=>$time_period,'tax'=>$tax);
		//reset items array incase of user error
		$this->items = array();
	}
	
	function OutputButton() {
		if (strlen($this->accountemail) > 0 && (count($this->items) > 0 || count($this->subscriptions) > 0)) {
			//ok
			if (strlen($this->target) > 0) {
				$this->target_text = ' target="' . $this->target . '"';
			}
			echo '<form action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post"' . $this->target_text . '>' . "\n";
			
			//ITEMS
			if (count($this->items) > 0) {
				//PURCHASE BUTTON
				if (count($this->items) == 1) {
					//only 1 item!
					echo '<input type="hidden" name="cmd" value="_xclick" />' . "\n";
					echo '<input type="hidden" name="item_name" value="' . $this->items[0]['name'] . '" />' . "\n";
					echo '<input type="hidden" name="quantity" value="' . $this->items[0]['qty'] . '" />' . "\n";
					echo '<input type="hidden" name="amount" value="' . $this->items[0]['price'] . '" />' . "\n";
					//item no
					if (strlen($this->items[0]['item_no']) > 0) {
						echo '<input type="hidden" name="item_number" value="' . $this->items[0]['item_no'] . '" />' . "\n";
					}
					//shipping
					if (strlen($this->items[0]['shipping']) > 0) {
						echo '<input type="hidden" name="shipping" value="' . $this->items[0]['shipping'] . '" />' . "\n";
					}
					//shipping2
					if (strlen($this->items[0]['shipping2']) > 0) {
						echo '<input type="hidden" name="shipping2" value="' . $this->items[0]['shipping2'] . '" />' . "\n";
					}
					//handling
					if (strlen($this->items[0]['handling']) > 0) {
						echo '<input type="hidden" name="handling" value="' . $this->items[0]['handling'] . '" />' . "\n";
					}
					//first field name
					if (strlen($this->items[0]['firstfieldname']) > 0) {
						echo '<input type="hidden" name="on0" value="' . $this->items[0]['firstfieldname'] . '" />' . "\n";
					}
					//first field options
					if (strlen($this->items[0]['firstfieldoptions']) > 0) {
						echo '<input type="hidden" name="os0" value="' . $this->items[0]['firstfieldoptions'] . '" />' . "\n";
					}
					//second field name
					if (strlen($this->items[0]['secondfieldname']) > 0) {
						echo '<input type="hidden" name="on1" value="' . $this->items[0]['secondfieldname'] . '" />' . "\n";
					}
					//second field options
					if (strlen($this->items[0]['secondfieldoptions']) > 0) {
						echo '<input type="hidden" name="os1" value="' . $this->items[0]['secondfieldoptions'] . '" />' . "\n";
					}
					
					
	
	
				} else {
					//more than 1 item
					echo '<input type="hidden" name="cmd" value="_cart" />' . "\n";
					echo '<input type="hidden" name="upload" value="1" />' . "\n";
					echo '<input type="hidden" name="item_name" value="Shopping Cart" />' . "\n";
					$totalamount = 0;
					for ($x=0;$x<count($this->items);$x++) {
						//for each item
						echo '<input type="hidden" name="item_name_' . ($x+1) . '" value="' . $this->items[$x]['name'] . '" />' . "\n";
						echo '<input type="hidden" name="quantity_' . ($x+1) . '" value="' . $this->items[$x]['qty'] . '" />' . "\n";
						echo '<input type="hidden" name="amount_' . ($x+1) . '" value="' . $this->items[$x]['price'] . '" />' . "\n";
						//item no
						if (strlen($this->items[$x]['item_no']) > 0) {
							echo '<input type="hidden" name="item_number_' . ($x+1) . '" value="' . $this->items[$x]['item_no'] . '" />' . "\n";
						}
						//shipping
						if (strlen($this->items[$x]['shipping']) > 0) {
							echo '<input type="hidden" name="shipping_' . ($x+1) . '" value="' . $this->items[$x]['shipping'] . '" />' . "\n";
						}
						//shipping2
						if (strlen($this->items[$x]['shipping2']) > 0) {
							echo '<input type="hidden" name="shipping2_' . ($x+1) . '" value="' . $this->items[$x]['shipping2'] . '" />' . "\n";
						}
						//handling
						if (strlen($this->items[$x]['handling']) > 0) {
							echo '<input type="hidden" name="handling_' . ($x+1) . '" value="' . $this->items[$x]['handling'] . '" />' . "\n";
						}
						//tax
						if (strlen($this->items[$x]['tax']) > 0) {
							echo '<input type="hidden" name="tax_' . ($x+1) . '" value="' . $this->items[$x]['tax'] . '" />' . "\n";
						}
						//first field name
						if (strlen($this->items[$x]['firstfieldname']) > 0) {
							echo '<input type="hidden" name="on0_' . ($x+1) . '" value="' . $this->items[$x]['firstfieldname'] . '" />' . "\n";
						}
						//first field options
						if (strlen($this->items[$x]['firstfieldoptions']) > 0) {
							echo '<input type="hidden" name="os0_' . ($x+1) . '" value="' . $this->items[$x]['firstfieldoptions'] . '" />' . "\n";
						}
						//second field name
						if (strlen($this->items[$x]['secondfieldname']) > 0) {
							echo '<input type="hidden" name="on1_' . ($x+1) . '" value="' . $this->items[$x]['secondfieldname'] . '" />' . "\n";
						}
						//second field options
						if (strlen($this->items[$x]['secondfieldoptions']) > 0) {
							echo '<input type="hidden" name="os1_' . ($x+1) . '" value="' . $this->items[$x]['secondfieldoptions'] . '" />' . "\n";
						}
						
						$totalamount = $totalamount + ($this->items[$x]['price'] * $this->items[$x]['qty']);
						
					}
					
					//generate total amount
					echo '<input type="hidden" name="amount" value="' . number_format($totalamount,2,".","") . '" />' . "\n";
				}
				//END ITEMS
			
			//END PURCHASE BUTTON
			} else {
							
				//SUBSCRIPTION BUTTON
				//only 1 item can be part of a subscription
				
				echo '<input type="hidden" name="cmd" value="_xclick-subscriptions" />' . "\n";
				echo '<input type="hidden" name="modify" value="1" />' . "\n"; //??
				echo '<input type="hidden" name="bn" value="PP-SubscriptionsBF" />' . "\n"; //???
				echo '<input type="hidden" name="src" value="1" />' . "\n"; //???
				echo '<input type="hidden" name="sra" value="1" />' . "\n"; //???
				
				echo '<input type="hidden" name="item_name" value="' . $this->subscriptions[0]['name'] . '" />' . "\n";
				echo '<input type="hidden" name="a3" value="' . $this->subscriptions[0]['price'] . '" />' . "\n";
				//item no
				if (strlen($this->subscriptions[0]['item_no']) > 0) {
					echo '<input type="hidden" name="item_number" value="' . $this->subscriptions[0]['item_no'] . '" />' . "\n";
				}
				//time_period
				if (strlen($this->subscriptions[0]['time_period']) > 0) {
					echo '<input type="hidden" name="t3" value="' . $this->subscriptions[0]['time_period'] . '" />' . "\n";
				}
				//interval
				if (strlen($this->subscriptions[0]['interval']) > 0) {
					echo '<input type="hidden" name="p3" value="' . $this->subscriptions[0]['interval'] . '" />' . "\n";
				}
				
				//END SUBSCRIPTION BUTTON
			}
			
			
			echo '<input type="hidden" name="business" value="' . $this->accountemail . '" />' . "\n";
			echo '<input type="hidden" name="currency_code" value="' . $this->currencycode . '" />' . "\n";

			//custom
			if (strlen($this->custom) > 0) {
				echo '<input type="hidden" name="custom" value="' . $this->custom . '" />' . "\n";
			}
			
			//image
			if (strlen($this->image) > 0) {
				echo '<input type="hidden" name="image_url" value="' . $this->image . '" />' . "\n";
			}		
		
			//askforaddress
			if ($this->askforaddress == true) {
				//ask for address
				echo '<input type="hidden" name="no_shipping" value="0" />' . "\n";
			} else {
				//don't ask for address
				echo '<input type="hidden" name="no_shipping" value="1" />' . "\n";
			}
			
			//note
			if ($this->askfornote == true) {
				//ask for note
				echo '<input type="hidden" name="no_note" value="0" />' . "\n";
			} else {
				//don't ask for note
				echo '<input type="hidden" name="no_note" value="1" />' . "\n";
			}
			
			//reutn url
			if (strlen($this->return_url) > 0) {
				echo '<input type="hidden" name="return" value="' . $this->return_url . '" />' . "\n";
			}
			//ipn url
			if (strlen($this->ipn_url) > 0) {
				echo '<input type="hidden" name="notify_url" value="' . $this->ipn_url . '" />' . "\n";
			}
			//cancel url
			if (strlen($this->cancel_url) > 0) {
				echo '<input type="hidden" name="cancel_return" value="' . $this->cancel_url . '" />' . "\n";
			}
			//class css
			if (strlen($this->class) > 0) {
				$class = ' class="' . $this->class . '"';
			} else {
				$class = '';
			}
			//css width
			$width = '';
			if (strlen($this->width) > 0) {
				$width = ' style="width:' . $this->width . 'px;"';
			}
			//button!
			if (strlen($this->buttonimage) > 0) {
				echo '<input type="image" src="' . $this->buttonimage . '" name="submit" alt="' . $this->buttontext . '"' . $class . $width . ' />' . "\n";
			} else {
				echo '<input type="submit" value="' . $this->buttontext . '" name="submit"' . $class . $width . ' />' . "\n";
			}
			echo '</form>';
			return true;
		} else {
			//not ok
			echo 'Error: $accountemail directive not set, or no items to sell!';
			return false;
		}
	}
	
	
	function OutputSubscriptionCancel() {
		//this function outputs a Cancel Subscription link.
		if (strlen($this->accountemail) == 0) {
			echo 'Error: $accountemail directive not set.';
			return false;
		} else {		
			$target_text = '';
			if (strlen($this->target) > 0) {
				$target_text = ' target="' . $this->target . '"';
			}
			
			//css width
			$width = '';
			if (strlen($this->width) > 0) {
				$width = ' style="width:' . $this->width . 'px;"';
			}
			
			//class css
			if (strlen($this->class) > 0) {
				$class = ' class="' . $this->class . '"';
			} else {
				$class = '';
			}
			
			if (strlen($this->buttonimage) > 0) {
				//use image
				$button = '<img src="' . $this->buttonimage . '" border="0" alt="' . $this->buttontext . '" />';
			} else {
				//use text
				$button = $this->buttontext;
			}
		
			//alias - this is an escaped version of the account email		
			$alias = urlencode($this->accountemail);
			//add some more urlencodes that the function does not do..
			$alias = str_replace(".","%2e",$alias);
			$alias = str_replace("-","%2d",$alias);
			
			echo '<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_subscr-find&alias=' . $alias . '"' . $target_text . $width . $class . ' tile="' . $this->buttontext . '">' . $button . '</a>';
			return true;
		}
	}
	
   function validate_ipn() {

      // parse the paypal URL
      $url_parsed=parse_url($this->paypal_url);        

      // generate the post string from the _POST vars aswell as load the
      // _POST vars into an arry so we can play with them from the calling
      // script.
      $post_string = '';    
      foreach ($_POST as $field=>$value) { 
         $this->ipn_data["$field"] = $value;
         $post_string .= $field.'='.urlencode($value).'&'; 
      }
      $post_string.="cmd=_notify-validate"; // append ipn command

      // open the connection to paypal
      $fp = fsockopen($url_parsed[host],"80",$err_num,$err_str,30); 
      if(!$fp) {
          
         // could not open the connection.  If loggin is on, the error message
         // will be in the log.
         $this->last_error = "fsockopen error no. $errnum: $errstr";
         $this->log_ipn_results(false);       
         return false;
         
      } else { 
 
         // Post the data back to paypal
         fputs($fp, "POST $url_parsed[path] HTTP/1.1\r\n"); 
         fputs($fp, "Host: $url_parsed[host]\r\n"); 
         fputs($fp, "Content-type: application/x-www-form-urlencoded\r\n"); 
         fputs($fp, "Content-length: ".strlen($post_string)."\r\n"); 
         fputs($fp, "Connection: close\r\n\r\n"); 
         fputs($fp, $post_string . "\r\n\r\n"); 

         // loop through the response from the server and append to variable
         while(!feof($fp)) { 
            $this->ipn_response .= fgets($fp, 1024); 
         } 

         fclose($fp); // close connection

      }
      
      if (eregi("VERIFIED",$this->ipn_response)) {
  
         // Valid IPN transaction.
         $this->log_ipn_results(true);
         return true;       
         
      } else {
  
         // Invalid IPN transaction.  Check the log for details.
         $this->last_error = 'IPN Validation Failed.';
         $this->log_ipn_results(false);   
         return false;
         
      }
      
   }
   
   function log_ipn_results($success) {
       
      if (!$this->ipn_log) return;  // is logging turned off?
      
      // Timestamp
      $text = '['.date('m/d/Y g:i A').'] - '; 
      
      // Success or failure being logged?
      if ($success) $text .= "SUCCESS!\n";
      else $text .= 'FAIL: '.$this->last_error."\n";
      
      // Log the POST variables
      $text .= "IPN POST Vars from Paypal:\n";
      foreach ($this->ipn_data as $key=>$value) {
         $text .= "$key=$value, ";
      }
 
      // Log the response from the paypal server
      $text .= "\nIPN Response from Paypal Server:\n ".$this->ipn_response;
      
      // Write to log
      $fp=fopen($this->ipn_log_file,'a');
      fwrite($fp, $text . "\n\n"); 
      fclose($fp);  // close file
   }
}
?>
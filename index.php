<?php

// index file ver 3.2
// if you are reading this, you should be working for us.

require ('../wp-config.php');
require ($_SERVER['DOCUMENT_ROOT'] . '/forms/checkout.php'); //root to server-side folder where bt subscriptions are created
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}
$sql1 = "SELECT MAX(memberid) FROM m_families";
$result = mysqli_query($conn, $sql1);
$new_member_number = mysqli_fetch_array($result)[0] + 1;
mysqli_free_result($result);
mysqli_close($conn);


/*
  	 * get_header is a basic wordpress function, used to retrieve the header.php file in your theme directory.
  	 */
get_header();
$categories = get_the_category(get_the_id());
$separator = ", ";
if ($categories) {
  foreach ($categories as $category) {
    $output .= '<a href="' . get_category_link($category->term_id) . '" title="' . esc_attr(sprintf(__("View all  posts in %s"), $category->name)) . '">' . $category->cat_name . '</a>' . $separator;
  }
  $output = trim($output, $separator);
}

//$title  = __('Blog - Latest News', 'avia_framework'); //default blog title
$title  = __($output, 'avia_framework'); //default blog title

$t_link = home_url('/');
$t_sub = "";

if (avia_get_option('frontpage') && $new = avia_get_option('blogpage')) {
  $title   = get_the_title($new); //if the blog is attached to a page use this title
  $t_link = get_permalink($new);
  $t_sub =  avia_post_meta($new, 'subtitle');
}

if (get_post_meta(get_the_ID(), 'header', true) != 'no') echo avia_title(array('title' => $title, 'link' => $t_link, 'subtitle' => $t_sub));

?>

<!doctype html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Home Education Network membership sign-up</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
  <script src="https://code.jquery.com/jquery-1.12.4.min.js" integrity="sha384-nvAa0+6Qg9clwYCGGPpDQLVpLNn0fRaROjHqs13t4Ggj3Ez50XnGQqc/r8MhnRDZ" crossorigin="anonymous"></script>
  <script src="repeater.js" type="text/javascript"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css" />

</head>
<!-- spinner style here -->
<style>

#button{
	display:block;
	margin:20px auto;
	padding:10px 30px;
	background-color:#eee;
	border:solid #ccc 1px;
  cursor: pointer;
}
#overlay{	
	position: fixed;
	top: 0;
	z-index: 100;
	width: 100%;
	height:100%;
	display: none;
	background: rgba(0,0,0,0.6);
}
.cv-spinner {
	height: 100%;
	display: flex;
	justify-content: center;
	align-items: center;  
}
.spinner {
	width: 40px;
	height: 40px;
	border: 4px #ddd solid;
	border-top: 4px #2e93e6 solid;
	border-radius: 50%;
	animation: sp-anime 0.8s infinite linear;
}
@keyframes sp-anime {
	0% { 
		transform: rotate(0deg); 
	}
	100% { 
		transform: rotate(359deg); 
	}
}
.is-hide{
	display:none;
}
/* The Modal (background) */
.modal {
  display: none; /* Hidden by default */
  position: fixed; /* Stay in place */
  z-index: 1; /* Sit on top */
  left: 0;
  top: 0;
  width: 100%; /* Full width */
  height: 100%; /* Full height */
  overflow: auto; /* Enable scroll if needed */
  background-color: rgb(0,0,0); /* Fallback color */
  background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
}

/* Modal Content/Box */
.modal-content {
  background-color: #fefefe;
  margin: 15% auto; /* 15% from the top and centered */
  padding: 20px;
  border: 1px solid #888;
  width: 80%; /* Could be more or less, depending on screen size */
}

/* The Close Button */
.close {
  color: #aaa;
  float: right;
  font-size: 28px;
  font-weight: bold;
}

.close:hover,
.close:focus {
  color: black;
  text-decoration: none;
  cursor: pointer;
}
</style>

<body>

<!-- Spinner Div --> 
<div id="overlay">
	<div class="cv-spinner">
		<span class="spinner"></span>
	</div>
</div>
<!-- The Modal -->
<div id="myModal" class="modal">

  <!-- Modal content -->
  <div class="modal-content" align="justify">
    <span class="close">&times;</span>
   <h3><strong><p>There has been an issue with your payment,the page will reload, please try your payment again or contact us directly @ emails@henireland.org </p></strong></h3>
  </div>

</div>

  <div class="container">
    <br />
    <h3 align="center">Home Education Network membership sign-up</h3>
    <br />
    <div style="width:100%; max-width: 800px; margin:0 auto;">
      <div class="panel panel-default">

        <span id="success_result"></span>
        <form method="post" id="repeater_form" action="<?php echo $baseUrl; ?>subscription.php">
          <input type="hidden" name="memberid" value="<?php echo ($new_member_number); ?>">
          <div class="panel-heading">Parents and guardians</div>
          <div class="panel-body">



            <!-- Parent -->
            <div class="form-group">
              <div class="clearfix"></div>
              <div class="items" data-group="parents">
                <div class="item-content">
                  <div class="form-group">
                    <div class="row">


                      <div class="col-md-3" align="center">
                        <label>First Name</label><br>
                          <input type="text" class="form-control" name="parent0fname" required />
                      </div>

                      <div class="col-md-4" align="center">
                        <label>Last Name</label><br>
                          <input type="text" class="form-control" name="parent0lname" required />
                      </div>
                      <div class="col-md-2" align="center">
                        <label>email</label><br>
                          <input type="text" class="form-control" name="parent0email" required />
                      </div>
                      <div class="col-md-2" align="center">
                        <label>contact&nbsp;&nbsp;</label><a href="#" data-toggle="tooltip" title="Can we use this email to contact you? We will use it for the newsletter and event announcements."><img src="question_mark16.png" height="16"></a><br>
                          <input type="checkbox" data-skip-name="true" name="parent0emailcontact" value="">
                      </div>

                    </div>





                    <div class="row">


                      <div class="col-md-3" align="center">
                        <label>phone</label><br>
                          <input type="text" class="form-control" name="parent0phone" required />
                      </div>

                      <div class="col-md-4" align="center">
                        <label>county</label><br>
                          <select class="form-control" name="parent0county" required>

                            <option value="Antrim">Antrim</option>
                            <option value="Armagh">Armagh</option>
                            <option value="Carlow">Carlow</option>
                            <option value="Cavan">Cavan</option>
                            <option value="Clare">Clare</option>
                            <option value="Cork">Cork</option>
                            <option value="Derry">Derry</option>
                            <option value="Donegal">Donegal</option>
                            <option value="Down">Down</option>
                            <option value="Dublin" selected="selected">Dublin</option>
                            <option value="Fermanagh">Fermanagh</option>
                            <option value="Galway">Galway</option>
                            <option value="Kerry">Kerry</option>
                            <option value="Kildare">Kildare</option>
                            <option value="Kilkenny">Kilkenny</option>
                            <option value="Laois">Laois</option>
                            <option value="Leitrim">Leitrim</option>
                            <option value="Limerick">Limerick</option>
                            <option value="Longford">Longford</option>
                            <option value="Louth">Louth</option>
                            <option value="Mayo">Mayo</option>
                            <option value="Meath">Meath</option>
                            <option value="Monaghan">Monaghan</option>
                            <option value="Offaly">Offaly</option>
                            <option value="Roscommon">Roscommon</option>
                            <option value="Sligo">Sligo</option>
                            <option value="Tipperary">Tipperary</option>
                            <option value="Tyrone">Tyrone</option>
                            <option value="Waterford">Waterford</option>
                            <option value="Westmeath">Westmeath</option>
                            <option value="Wexford">Wexford</option>
                            <option value="Wicklow">Wicklow</option>
                          </select>
                      </div>
                      <div class="col-md-2" align="center">
                        <label>post&nbsp;&nbsp;</label><a href="#" data-toggle="tooltip" title="We can post the newsletter to the primary address (used during the checkout)"><img src="question_mark16.png" height="16"></a><br>
                        <div class="checkbox" align="center">
                          <input type="checkbox" data-skip-name="true" name="parent0newslettermail" id="parent0newslettermail">
                        </div>
                      </div>

                    </div>



                    <div class="row">


                      <div class="col-md-12" align="center">
                        <label>Postal Address</label>
<a href="#" data-toggle="tooltip" title="If you choose to receive a paper copy of the newsletter, we will need your postal address."><img src="question_mark16.png" height="16"></a>
<br>
                        <textarea rows="5" class="form-control" name="postaladdress" id="PostalAddress" disabled >
Enter your postal address here, if you would like to receive a paper copy of the newsletter.
</textarea>
                      </div>
                    </div>

                    <!--  PARENT second ...................................................... -->
                    <br>

                    <div class="row">


                      <div class="col-md-3" align="center">
                        <label>First Name</label><br>
                          <input type="text" class="form-control" name="parent1fname" />
                      </div>

                      <div class="col-md-4" align="center">
                        <label>Last Name</label><br>
                          <input type="text" class="form-control" name="parent1lname" />
                      </div>
                      <div class="col-md-2" align="center">
                        <label>email</label><br>
                          <input type="text" class="form-control" name="parent1email" />
                      </div>
                      <div class="col-md-2" align="center">
                        <label>contact&nbsp;&nbsp;</label><a href="#" data-toggle="tooltip" title="Can we use this email to contact you? We will use it for the newsletter and event announcements."><img src="question_mark16.png" height="16"></a>
                        <br>
                          <div class="checkbox" align="center">
                            <input type="checkbox" data-skip-name="true" name="parent1emailcontact" value="">
                          </div>
                      </div>
                    </div>


                    <div class="row">


                      <div class="col-md-3" align="center">
                        <label>phone</label><br>
                          <input type="text" class="form-control" name="parent1phone" required />
                      </div>

                      <div class="col-md-4" align="center">
                        <label>county</label><br>
                          <select class="form-control" name="parent1county" required>

                            <option value="Antrim">Antrim</option>
                            <option value="Armagh">Armagh</option>
                            <option value="Carlow">Carlow</option>
                            <option value="Cavan">Cavan</option>
                            <option value="Clare">Clare</option>
                            <option value="Cork">Cork</option>
                            <option value="Derry">Derry</option>
                            <option value="Donegal">Donegal</option>
                            <option value="Down">Down</option>
                            <option value="Dublin" selected="selected">Dublin</option>
                            <option value="Fermanagh">Fermanagh</option>
                            <option value="Galway">Galway</option>
                            <option value="Kerry">Kerry</option>
                            <option value="Kildare">Kildare</option>
                            <option value="Kilkenny">Kilkenny</option>
                            <option value="Laois">Laois</option>
                            <option value="Leitrim">Leitrim</option>
                            <option value="Limerick">Limerick</option>
                            <option value="Longford">Longford</option>
                            <option value="Louth">Louth</option>
                            <option value="Mayo">Mayo</option>
                            <option value="Meath">Meath</option>
                            <option value="Monaghan">Monaghan</option>
                            <option value="Offaly">Offaly</option>
                            <option value="Roscommon">Roscommon</option>
                            <option value="Sligo">Sligo</option>
                            <option value="Tipperary">Tipperary</option>
                            <option value="Tyrone">Tyrone</option>
                            <option value="Waterford">Waterford</option>
                            <option value="Westmeath">Westmeath</option>
                            <option value="Wexford">Wexford</option>
                            <option value="Wicklow">Wicklow</option>
                          </select>
                      </div>
                      <div class="col-md-2" align="center">
                        <label></label><br>
                      </div>

                    </div>



                  </div>
                </div>
              </div>


              <div class="row">
                <div class="col-md-10">
                  <div class="form-group">
                    <!-- Date input -->
                    <label class="control-label" for="date">Date joined</label><a href="#" data-toggle="tooltip" title="Have you joined HEN before renwing your subscription today?"><img src="question_mark16.png" height="16"></a>
                    <input class="form-control" id="date" name="date" placeholder="DD/MM/YYY" type="text" />
                  </div>
                </div>
              </div>
            </div>
          </div>


          <div class="panel-heading">Child(ren)</div>
          <div class="panel-body">

            <!-- Child -->

            <div id="repeater">
              <div class="row" valign="bottom">
                <div class="col-md-3" align="center">
                  <label>First Name</label><a href="#" data-toggle="tooltip" title="We would like to know your child(ren)'s first name so they can take part in our events."><img src="question_mark16.png" height="16"></a>
                </div>

                <div class="col-md-2" align="center">
                  <label>Birth year</label><a href="#" data-toggle="tooltip" title="By knowing how old your child is, we may be able to suggest age-appropriate activities or tailor our events. This data also helps us to understand our community's composition better."><img src="question_mark16.png" height="16"></a>
                </div>
                <div class="col-md-2" align="center">
                  <label>Sex</label><a href="#" data-toggle="tooltip" title="Like with 'Birth year', this data helps in our understanding of the community."><img src="question_mark16.png" height="16"></a>
                </div>
                <div class="col-md-2" align="right">
                  <div class="repeater-heading" align="right">
                    <button type="button" class="btn btn-primary repeater-add-btn">Add</button>
                  </div>
                </div>
              </div>

              <br>
              <div class="clearfix"></div>
              <div class="items" data-group="children">
                <div class="item-content">
                  <div class="form-group">



                    <div class="row">
                      <!-- I need to remember cols must total to 12 -->
                      <div class="col-md-3">
                        <input type="text" class="form-control" data-skip-name="true" data-name="childfname[]" required />
                      </div>
                      <!--
                                            <div class="col-md-4">
                                              <input type="text" class="form-control" data-skip-name="true" data-name="childlname[]" required />
                                            </div> -->
                      <div class="col-md-2">
                        <input type="text" class="form-control" data-skip-name="true" data-name="childyb[]" required />
                      </div>
                      <div class="col-md-2">
                        <div class="form-group">
                          <select class="form-control" class="form-control" data-skip-name="true" data-name="childgender[]">
                            <option>-</option>
                            <option>F</option>
                            <option>M</option>
                          </select>
                        </div>

                      </div>
                      <div class="col-md-2" align="right">
                        <button id="remove-btn" class="btn btn-danger" onclick="$(this).parents('.items').remove()">Remove</button>
                      </div>
                    </div>


                  </div>
                </div>
              </div>
            </div>
            <div class="bt-drop-in-wrapper">
            <label for="card-number">Card Number</label> 
                <div id="card-number"></div> 
            
                <label for="cvv">CVV</label> 
                <div id="cvv"></div> 
            
                <label for="expiration-date">Expiration Date</label> 
                <div id="expiration-date"></div> 
 
	  

            <input id="nonce" name="nonce" autocomplete="false" type="hidden" />
            <button class="button" id="submit-button" type="submit" disabled><span>Subscribe</span></button>





            <div class="clearfix"></div>

          </div>
        </form>



      </div>
    </div>


    <!-- gonna need hosted fields for 3DS >:( -->
    <!-- Load the client component. --> 
<script src="https://js.braintreegateway.com/web/3.50.1/js/client.min.js"></script> 
 
 <!-- Load the Hosted Fields component.. --> 
 <script src="https://js.braintreegateway.com/web/3.50.1/js/hosted-fields.min.js"></script> 
  
 <!-- Load the 3D Secure component. --> 
 <script src="https://js.braintreegateway.com/web/3.50.1/js/three-d-secure.min.js"></script> 
   <script> 
   var form = document.querySelector('#repeater_form');
   var button = document.querySelector('#submit-button');
      
  
     braintree.client.create({ 
      authorization: "<?php echo ($gateway->ClientToken()->generate()); ?>",
     }, function (clientErr, clientInstance) { 
     if (clientErr) { 
     console.error(clientErr); 
     return; 
     } 
    
     braintree.threeDSecure.create({ 
     version: 2, // Will use 3DS 2 whenever possible 
     client: clientInstance 
     }, function (threeDSecureErr, threeDSecureInstance) { 
   if (threeDSecureErr) { 
     // Handle error in 3D Secure component creation 
     return; 
   } 
  
   threeDSecure = threeDSecureInstance; 
   }); 
  
   // This example shows Hosted Fields, but you can also use this 
   // client instance to create additional components here, such as 
   // PayPal or Data Collector. 
  
   braintree.hostedFields.create({ 
     client: clientInstance, 
     styles: { 
   'input': { 
     'height': '100% !important',
     'font-size': '14px' 
   }, 
   'input.invalid': { 
     'color': 'red' 
   }, 
   'input.valid': { 
     'color': 'green' 
   } 
     }, 
     fields: { 
   number: { 
     selector: '#card-number', 
     placeholder: '4111 1111 1111 1111' 
   }, 
   cvv: { 
     selector: '#cvv', 
     placeholder: '123' 
   }, 
   expirationDate: { 
     selector: '#expiration-date', 
     placeholder: '10/2019' 
   } 
     } 
   }, function (hostedFieldsErr, hostedFieldsInstance) { 
     if (hostedFieldsErr) { 
   console.error(hostedFieldsErr); 
   return; 
     } 
  
     button.removeAttribute('disabled'); 
  
     form.addEventListener('submit', function (event) { 
     event.preventDefault();
     $("#overlay").fadeIn(300);　
  
     hostedFieldsInstance.tokenize(function (tokenizeErr, payload) { 
     if (tokenizeErr) { 
    
       return; 
       } 
        // ajax call to server to pass nonce and mabe some customer information. 
     // then we will retreive a new nonce from the stored pmt to pass to the 3DS verification
     
     $.ajax({
              type: 'POST',
              url: 'checkout.php',
              //pass data with the post call 
              data: { 'paymentMethodNonce': payload.nonce}
            }).done(function (result){
              
              var nonceForEnrichment = result;
              
	              
	                            
            
            
            
  
     // If this was a real integration, this is where you would 
     // send the nonce to your server. 
     ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
     // new nonce to be inserted here 
     threeDSecure.verifyCard({ 
     amount: '25.00', 
     nonce: nonceForEnrichment, // Example: hostedFieldsTokenizationPayload.nonce 
     bin: payload.details.bin, // Example: hostedFieldsTokenizationPayload.details.bin 
     /** 
     * @function onLookupComplete 
     * Newly required in `verifyCard` options object, will be called after receiving ThreeDSecure 
     * response, before completing the flow. 
     * @param {object} data - ThreeDSecure data to consume before continuing 
     * @param {string} data.paymentMethod.nonce - payment nonce 
     * @param {object} data.threeDSecureInfo 
     * @param {boolean} data.threeDSecureInfo.liabilityShifted 
     * @param {boolean} data.threeDSecureInfo.liabilityShiftPossible 
     * @param {function} next - callback to continue flow 
     * */ 
     onLookupComplete: function (data, next) { 
      var postingnonce = data.paymentMethod.nonce
      document.querySelector('#nonce').value = postingnonce;
     next(); 
     //form.submit();
     } 
   }, function (err, response) { 
     console.log(response.liabilityShiftPossible);
     console.log(response.liabilityShifted);
     $("#overlay").fadeOut(300);    

     
    if((response.liabilityShiftPossible == true) && (response.liabilityShifted == true)){

      form.submit();
      button.addAttribute('disabled');
    }
    else{

      console.log("3d Secure issue with liability shift"); 
       modal.style.display = "block";

    }
    

   }); 

  });//this is the end of the ajax call
   }); /// this is the end of the tokeinze method 
     }, false); 
   }); 
     }); 
   </script> 



    <script>
    document.getElementById('parent0newslettermail').onchange = function() {
        document.getElementById('PostalAddress').disabled =
!this.checked;
        console.log("Checkbox toggled");
    };
    $(document).ready(function(){

        $("#repeater").createRepeater();

        $('#repeater_form').on('submit', function(event){
            event.preventDefault();
            $.ajax({
                url:"insert2.php", // insert.php",
                method:"POST",
                data:$(this).serialize(),
                success:function(data)
                {
                    $('#repeater_form')[0].reset();
                    $("#repeater").createRepeater();
                    $('#success_result').html(data);
                    /*setInterval(function(){
                        location.reload();
                    }, 3000);*/
                }
            });
			$('#repeater_form').find('button[type=submit]').prop('disabled', true);
        });


        // here below is the datepicker. Also prone to spontaneous combustion.
        var date_input=$('input[name="date"]'); //our date input has the name "date"
        var container=$('.bootstrap-iso form').length>0 ? $('.bootstrap-iso form').parent() : "body";
        var options={
          format: 'dd/mm/yyyy',
          container: container,
          todayHighlight: true,
          autoclose: true,
        };
        date_input.datepicker(options);


        // $('[data-toggle="tooltip"]').tooltip(); // we are enabling tooltips here. this line was stitched on and will likely cause a disaster if you feed it after midnight.
          // TODO: THis is throwing exceptions, complaining tooltip is not a function.
    });

-->
    </script>

  


    <?php get_footer(); ?>

</body>

<!-- THIS IS THE SPINNER DELETE IF NECESSARY --> 
<script>
 var modal = document.getElementById("myModal");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];


// When the user clicks on <span> (x), close the modal
span.onclick = function() {
  $("#overlay").fadeIn(300);　
  window.location.reload(true);
}


// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
  if (event.target == modal) {
    $("#overlay").fadeIn(300);　
    window.location.reload(true);
  }
}
</script>

</html>